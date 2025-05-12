<?php

use App\Http\Requests\ParcelRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ShippingRateController;
use App\Http\Controllers\BalanceDepositController;
use App\Http\Controllers\PaymentInvoiceController;
use App\Http\Controllers\StorageInvoiceController;
use App\Http\Controllers\Payment\OrdersSelectController;
use App\Http\Controllers\Payment\OrdersInvoiceController;
use App\Http\Controllers\Payment\OrdersCheckoutController;
use App\Http\Controllers\Payment\PaymentInvoiceExportController;
use App\Http\Controllers\Payment\StorageInvoiceCheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/dashboard', [OrderController::class, 'getOrderCount'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('invoices', PaymentInvoiceController::class)->only(['index', 'show']);
    Route::resource('balances', BalanceController::class)->only(['index']);
    Route::resource('deposits', BalanceDepositController::class)->only(['index', 'store']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/get-states/{countryId}', [ProfileController::class, 'getStates'])->name('profile.country.state');
    Route::resource('users', UserController::class);
    Route::post('/user/rates', [UserController::class, 'updateUserRates'])->name('user.rates');
    Route::get('/admin/impersonate/{user}', [UserController::class, 'impersonate'])->name('user.admin.impersonate');
    Route::get('/admin/stop-impersonate', [UserController::class, 'stopImpersonate'])->name('user.admin.stopImpersonate');
    Route::get('/get-states/{countryId}', [UserController::class, 'getStates'])->name('user.country.state');
    Route::resource('roles', RoleController::class);
    Route::get('/edit-permission/{id}', [RoleController::class, 'editPermissions'])->name('roles.edit.permissions');
    Route::put('/update-permission/{id}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
    Route::resource('products', ProductController::class);
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::get('/products/{id}/check-in', [ProductController::class, 'warehouseCheckIn'])->name('product.checkin');
    Route::post('/products/units/dispatch', [ProductController::class, 'dispatchUnits'])->name('product.units.dispatch');
    Route::post('/products/check-in/update', [ProductController::class, 'checkInUpdate'])->name('product.checkInUpdate');
    Route::get('product/{id}/price', [ProductController::class, 'getPrice'])->name('product.getPrice');
    Route::post('product/{id}/price', [ProductController::class, 'updatePrice'])->name('product.updatePrice');
    Route::get('product/{id}/orders', [ProductController::class, 'productOrders'])->name('product.orders');

    
    Route::resource('rates', RateController::class);
    Route::get('rate/{shipping_service}/change-status', [RateController::class, 'changeStatus'])->name('rate.change-status');
    Route::get('/get-invoice/{id}', [ProductController::class, 'getInvoice'])->name('orders.invoice');
    Route::get('/view-invoice/{id}/{type}', [TransactionController::class, 'viewInvoice'])->name('transactions.invoice');
    Route::get('/get-address/{id}', [ProductController::class, 'getAddress'])->name('parcel.address.list');
    
    Route::prefix('product')->group(function () {
        Route::post('/order-placed', [ProductController::class, 'orderPlaced'])->name('product.order.placed');
        Route::get('/get-states/{countryId}', [ProductController::class, 'getStates'])->name('product.country.states');
        Route::get('/get-services-rates/{orderId}', [ProductController::class, 'getServicesRates'])->name('product.services.rates');
        Route::get('/get-shcodes', [ProductController::class, 'getShCodes'])->name('product.shcodes');
    
        Route::prefix('{id}/order')->group(function () {
            Route::get('/units', [ProductController::class, 'unitsInfo'])->name('product.order.units');
            Route::get('/recipient-details', [ProductController::class, 'recipientDetails'])->name('product.order.details');
            Route::get('/details', [ProductController::class, 'orderDetails'])->name('product.order.details');
            Route::get('/invoice', [ProductController::class, 'invoice'])->name('product.invoice.details');
        });
    
        Route::post('/order-details/store/', [ProductController::class, 'storeOrderDetails'])->name('product.order.details.store');
        Route::post('/recipient-details/store/{orderId}', [ProductController::class, 'storeRecipientDetails'])->name('product.recipient.details.store');
        Route::post('/shipping-items-details/store/{orderId}', [ProductController::class, 'storeShippingItems'])->name('product.shipping.items.store');
        Route::post('/order-details/edit/{orderId}', [OrderController::class, 'updateOrderDetails'])->name('product.order.details.edit');

    });
    
    Route::resource('orders', OrderController::class);
    Route::get('/get-order-count', [OrderController::class, 'getOrderCount'])->name('get-order-count');
    Route::prefix('order')->group(function () {
        Route::get('/pay/{order}', [OrderController::class, 'payOrder'])->name('order.pay');
        Route::get('/register/{orderId}', [OrderController::class, 'registerOrder'])->name('order.register');
        Route::get('/cancel/{order}', [OrderController::class, 'cancelOrder'])->name('order.cancel');
        Route::get('/print-label/{orderId}', [OrderController::class, 'printLable'])->name('order.print.label');
        Route::get('/reload-label/{orderId}', [OrderController::class, 'reloadLable'])->name('order.reload.label');
    });
    Route::post('/orders/{order}/add-item', [OrderController::class, 'addItem'])->name('order.add.item');
    Route::post('/orders/{order}/edit-item/{item}', [OrderController::class, 'editItem'])->name('order.edit.item');
    Route::delete('/orders/{order}/delete-item/{item}', [OrderController::class, 'deleteItem'])->name('order.delete.item');

    Route::post('/orders/ship', [OrderController::class, 'shipOrder'])->name('orders.ship');

    Route::get('/pending-orders', [OrderController::class, 'pending'])->name('orders.pending');
    Route::get('/shipped-orders', [OrderController::class, 'shipped'])->name('orders.shipped');

    Route::resource('payment-invoices', PaymentInvoiceController::class)->only(['index','store','destroy', 'create', 'update']);
    Route::prefix('payment-invoices')->as('payment-invoices.')->group(function () {
        Route::resource('orders', OrdersSelectController::class)->only(['index','store']);
        Route::resource('invoice', OrdersInvoiceController::class)->only(['show','store','edit','update']);
        Route::resource('invoice.checkout', OrdersCheckoutController::class)->only(['index','store']);
        Route::get('exports', PaymentInvoiceExportController::class)->name('exports');
    });
    Route::delete('/payment-invoices/{invoice}', [PaymentInvoiceController::class, 'destroy'])->name('payment-invoices.destroy');

    Route::post('/create-payment-intent', [OrdersCheckoutController::class, 'createPaymentIntent']);
    Route::get('/payment-intent-process', [OrdersCheckoutController::class, 'handlePaymentReturn'])->name('payment-invoices.process');

    Route::post('/invoice/checkout/upload-receipt', [OrdersCheckoutController::class, 'uploadBankReceipt'])->name('checkout.uploadReceipt');
    Route::post('/invoice/checkout/add-payment', [OrdersCheckoutController::class, 'addInvoicePayment'])->name('checkout.addPayment');


    Route::resource('transactions', TransactionController::class);
    Route::post('/transactions/confirm/{transaction}/{type}', [TransactionController::class, 'confirmPayment'])->name('transactions.confirm');
    Route::post('/transactions/decline/{transaction}/{type}', [TransactionController::class, 'declinePayment'])->name('transactions.decline');
    Route::post('/transaction/{id}/ship', [TransactionController::class, 'shipOrders'])->name('transaction.shipOrders');

    //Storage Invoice Routes
    Route::resource('storage-invoices', StorageInvoiceController::class)->only(['index','store','show','destroy']);
    Route::post('storage-invoices/create', [StorageInvoiceController::class, 'generateInvoices'])->name('storageinvoices.create');
    Route::prefix('storage-invoices')->as('storage-invoices.')->group(function () {
        Route::resource('invoice.checkout', StorageInvoiceCheckoutController::class)->only(['index','store']);
    });
    Route::get('/get-storage-invoice/{id}/{type}', [StorageInvoiceController::class, 'getStorageInvoice'])->name('storage.invoice');
    Route::delete('/storage-invoices/{invoice}', [StorageInvoiceController::class, 'destroy'])->name('storage-invoices.destroy');
    Route::post('/create-storage-invoice-payment-intent', [StorageInvoiceCheckoutController::class, 'createStorageInvoicePaymentIntent']);
    Route::get('/storage-invoice-payment-intent-process', [StorageInvoiceCheckoutController::class, 'handlePaymentReturn'])->name('storage-invoices.process');
    Route::post('/storage-invoice/checkout/upload-receipt', [StorageInvoiceCheckoutController::class, 'uploadBankReceipt'])->name('storageInvoiceCheckout.uploadReceipt');


    Route::resource('trackings', TrackingController::class)->only('index');
    Route::get('/tracking-order', [TrackingController::class, 'trackOrder'])->name('tracking.order.details');
    Route::resource('settings', SettingController::class);
    // Ajax Handling
    Route::get("get-setting-token-active",[SettingController::class,"GetSettingToken"])->name("get-setting-token-active");
    Route::get("update-token/{tokenMode}",[SettingController::class,"updateToken"])->name("update-token");
    Route::resource('addresses', AddressController::class);
    Route::get('/get-addresses/{id}', [AddressController::class, 'getAddresses'])->name('addresses.address.list');
    Route::get('/get-states/{countryId}', [AddressController::class, 'getStates'])->name('addresses.country.states');
    Route::get("/export-User",[UserController::class,"exportUser"])->name("export.user");
    Route::post("/export-order",[OrderController::class,"exportOrder"])->name('export.order');
    Route::post("/export-invoice",[PaymentInvoiceController::class,"exportInvoice"])->name('export.invoice');
    Route::post("/export-balance",[BalanceController::class,"exportBalance"])->name('export.balance');
    Route::get('/invoice/download/{id}', [PaymentInvoiceController::class, 'downloadInvoice'])->name('invoice.download');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');

});

require __DIR__ . '/auth.php';
