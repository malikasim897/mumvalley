<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\InvoiceOrder;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use App\Models\UserProductBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\ProductRepository;
use App\Services\Excel\Export\ExportOrder;
use App\Http\Requests\Parcel\ParcelRequest;

class OrderController extends Controller
{

    protected $productRepository;
    protected $orderRepository;
    protected $apiRepository;
    protected $userRepository;

    function __construct(
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        UserRepository $userRepository
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->middleware('permission:order.view|order.create|order.edit|order.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:order.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:order.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:order.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders.index');
    }

    public function pending()
    {
        return view('orders.pending');
    }

    public function shipped()
    {
        return view('orders.shipped');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = $this->userRepository->get();
        $products = $this->productRepository->getProducts();
        return view('orders.create', compact('products','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateOrderDetails(Request $request, $orderId)
    {
        try {
            // Validate that at least one checkbox is checked
            // if (!$request->hasAny(['fnsku', 'bubblewrap', 'polybag', 'small_box', 'medium_box', 'large_box', 'additional_units'])) {
            //     return redirect()->back()->withInput()->with('error', 'Please select at least one service.');
            // }

            $request->validate([
                'shipment_label' => 'file|mimes:pdf|max:5120', 
            ], [
                'shipment_label.mimes' => 'The shipment label must be a PDF file.',
                'shipment_label.max' => 'The shipment label must not exceed 5MB.',
            ]);

            // Call the repository method to create or update the order
            $orderId = $this->orderRepository->updateOrderDetails($request, $orderId);

            if ($orderId) {
                return redirect()->route('product.invoice.details', ['id' => Crypt::encrypt($orderId)]);
            } else {
                return redirect()->back()->withInput()->with('error', 'There was an issue processing your request.');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('An exception occurred: ' . $errorMessage);
            return back()->withInput()->with('error', 'An error occurred: ' . $errorMessage);
        }
    }
    /**
     * Remove the specified resource from storage.
     */

     public function cancelOrder(Order $order)
    {
        if ($order->order_status == 'cancelled') {
            return redirect()->back()->with('error', 'Order is already cancelled');
        }

        try {
            // Check if the order hasn't been paid for
            if (!$order->payment_status) {
                // Loop through each order item
                foreach ($order->items as $orderItem) {
                    // Get the latest confirmed inventory for the product
                    $latestConfirmedInventory = $orderItem->product->latestConfirmedInventory;

                    if ($latestConfirmedInventory) {
                        // Update remaining units for the inventory
                        $latestConfirmedInventory->update([
                            'remaining_units' => ($latestConfirmedInventory->remaining_units + $orderItem->delivered_units) - $orderItem->returned_units,
                        ]);
                    }
                }

                // Detach the order from the invoice(s)
                foreach ($order->paymentInvoices as $invoice) {
                    $invoice->orders()->detach($order->id);

                    // If the invoice has only this order and is not paid, cancel the invoice
                    if ($invoice->orders()->count() === 0 && !$invoice->is_paid) {
                        $invoice->update(['cancelled' => true]);
                        $invoice->delete();
                    } else {
                        // Optionally, update the invoice total amount
                        $totalAmount = $invoice->orders()->sum('total_amount');
                        $invoice->update(['total_amount' => $totalAmount]);
                    }
                }

                // Update order status to cancelled
                $order->update(['order_status' => 'cancelled']);

                return redirect()->back()->with('success', 'Order cancelled successfully');
            } else {
                return redirect()->back()->with('error', 'Order has already been paid and cannot be cancelled.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Loop through each order item
            foreach ($order->items as $orderItem) {
                // Check if there's a confirmed inventory to update for the product
                $latestConfirmedInventory = $orderItem->product->latestConfirmedInventory;

                if ($latestConfirmedInventory) {
                    // Update remaining units
                    $latestConfirmedInventory->update([
                        'remaining_units' => ($latestConfirmedInventory->remaining_units + $orderItem->delivered_units) - $orderItem->returned_units,
                    ]);
                }
            }

            // Delete the order
            $order->delete();

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
        } catch (\Exception $e) {
            // Return the error message to the user
            return redirect()->route('orders.index')->with('error', 'An error occurred while deleting the order: ' . $e->getMessage());
        }
    }


    /**
     * Check if the API response is valid.
     *
     * @param array $response
     * @return bool
     */

     public function getOrderCount()
     {                                                                                                               
         $paymentDoneCount = $this->orderRepository->getOrderCount();
         $todayOrders = $this->orderRepository->getTodayOrderCount();
         $currentMonthOrder = $this->orderRepository->getCurrentMonthOrderCount();
         $currentYearOrder = $this->orderRepository->getCurrentYearCount();
         $since2024Order = $this->orderRepository->getSince2024Count();

         $totalProducts = $this->orderRepository->gettotalProducts();
         $pendingInventory = $this->orderRepository->getpendingInventory();
         $totalInProcessOrders = $this->orderRepository->gettotalInProcessOrders();
         $transactionsSucceeded = $this->orderRepository->gettransactionsSucceeded();
         $transactionPending = $this->orderRepository->gettransactionPending();
         $paidInvoices = $this->orderRepository->paidInvoices();
         $pendingInvoices = $this->orderRepository->pendingInvoices();

         $totalUsers = $this->orderRepository->getTotalUsers();
         $totalPaidAmount = $this->orderRepository->getTotalPaidAmount();
         $totalPendingAmount = $this->orderRepository->getTotalRemainingAmount();
         $totalReturnableBottles = $this->orderRepository->getTotalReturnableBottles();
         $partialPaidInvoices = $this->orderRepository->getTotalPartialPaidInvoices();


        //  dd($currentMonthOrder);
         return view('dashboard', compact('paymentDoneCount', 'todayOrders', 'currentMonthOrder', 'currentYearOrder',
          'since2024Order', 'totalProducts', 'pendingInventory', 'totalInProcessOrders', 'transactionsSucceeded',
           'transactionPending', 'paidInvoices', 'pendingInvoices', 'totalUsers', 'totalPaidAmount', 'totalPendingAmount', 'totalReturnableBottles', 'partialPaidInvoices'));
     
     }

    public function exportOrder(Request $request)
    {
        $exportUsers = new ExportOrder(
            $this->orderRepository->getExportOrder($request)
        );
        return $exportUsers->handle();
    }

    public function addItem(Request $request, $orderId)
    {
        try {
            $packQuantity = 0;
            $order = Order::findOrFail($orderId);
            $product = Product::findOrFail($request->product_id);
            $deliveredUnits =  $request->delivered_units;
            $returnedUnits =  $request->returned_units;
            $unitPrice =  $request->unit_price;

            // Check if the product is already added in the order->items
            if ($order->items()->where('product_id', $product->id)->exists()) {
                return response()->json(['error' => 'Product is already added to the order.'], 422);
            }

            if ($product) {

                $totalPrice = $unitPrice * $deliveredUnits; // Calculate total price for the shipped units
                // Format prices to two decimal places
                $unitPrice = number_format($unitPrice, 2, '.', '');
                $totalPrice = number_format($totalPrice, 2, '.', '');

                if ($product->type === 'pack_of_6') {
                    $deliveredUnits = $deliveredUnits * 6;
                    $packQuantity = $request->delivered_units;
                }

                if ($product->type === 'pack_of_12') {
                    $deliveredUnits = $deliveredUnits * 12;
                    $packQuantity = $request->delivered_units;
                }

                // Create the order item
                $orderItem = $order->items()->create([
                    'product_id' => $product->id,
                    'product_inventory_id' => $product->latestConfirmedInventory->id,
                    'delivered_units' => $deliveredUnits,
                    'returned_units' => $returnedUnits,
                    'remaining_customer_units' => 0, // temp
                    'remaining_stock_units' => ($product->latestConfirmedInventory->remaining_units - $deliveredUnits) + $returnedUnits,
                    'pack_quantity' => $packQuantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                if ($product->type === 'pack_of_1' && $product->returnable) {
                    // Fetch the latest previous balance if exists
                    $lastBalance = UserProductBalance::where('user_id', $request->user_id)
                        ->where('product_id', $product->id)
                        ->latest('id')
                        ->first();
                
                    $previousRemaining = $lastBalance ? $lastBalance->remaining_units : 0;
                    $newRemaining = ($previousRemaining + $deliveredUnits) - $returnedUnits;
                
                    UserProductBalance::create([
                        'user_id' => $request->user_id,
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id,
                        'remaining_units' => $newRemaining
                    ]);
                
                    // Update order item as well with snapshot
                    $orderItem->update(['remaining_customer_units' => $newRemaining]);
                }

                // Update remaining units in inventory
                $product->latestConfirmedInventory->update([
                    'remaining_units' => ($product->latestConfirmedInventory->remaining_units - $deliveredUnits) + $returnedUnits,
                ]);

                $order->total_amount = $order->items()->sum('total_price');
                $order->save();

                if ($order->getPaymentInvoice()) {
                    // Call updateInvoiceTotalAmount if there is a payment invoice
                    $order->updateInvoiceTotalAmount();
                }
            }

            return response()->json(['success' => 'Item added to the order successfully!'], 200);
        } catch (\Exception $e) {
            // Catch any exception and return a JSON error response
            return response()->json([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    public function editItem(Request $request, $orderId, $itemId)
    {
        try {
            $packQuantity = 0;

            // Fetch order item, product, and order
            $orderItem = OrderItem::findOrFail($itemId);
            $product = $orderItem->product;
            $order = $orderItem->order;
            $latestInventory = $product->latestConfirmedInventory;

            // Validate shipped units and price
            $request->validate([
                'shipped_units' => 'required|integer|min:1',
                'unit_price' => 'required|numeric',
            ]);

            // Get inputs
            $inputShipped = (int) $request->input('shipped_units');
            $unitPrice = (float) $request->input('unit_price');
            $returnedUnits = (int) $request->input('returned_units');

            // Preserve previous values
            $oldDelivered = $orderItem->delivered_units ?? 0;
            $oldReturned = $orderItem->returned_units ?? 0;

            // Apply packaging logic
            if ($product->type === 'pack_of_6') {
                $packQuantity = $inputShipped;
                $deliveredUnits = $inputShipped * 6;
            } elseif ($product->type === 'pack_of_12') {
                $packQuantity = $inputShipped;
                $deliveredUnits = $inputShipped * 12;
            } else {
                $deliveredUnits = $inputShipped;
            }

            // Check if shipped units exceed available units
            if ($deliveredUnits > $latestInventory->remaining_units + $oldDelivered) {
                return response()->json(['error' => 'Delivered units exceed the available remaining units (' . $latestInventory->remaining_units . ').'], 422);
            }

            // Calculate total price
            $totalPrice = number_format($unitPrice * $inputShipped, 2, '.', '');
            $unitPriceFormatted = number_format($unitPrice, 2, '.', '');

            // --- Update inventory delta ---
            $inventoryDelta = ($oldDelivered - $deliveredUnits) + ($returnedUnits - $oldReturned);
            $newInventoryRemaining = $latestInventory->remaining_units + $inventoryDelta;

            $latestInventory->update([
                'remaining_units' => $newInventoryRemaining,
            ]);

            // --- Handle returnable product user balance ---
            if ($product->type === 'pack_of_1' && $product->returnable) {

                $lastBalance = UserProductBalance::where('user_id', $order->user_id)
                    ->where('order_id', $order->id)    
                    ->where('product_id', $product->id)
                    ->where('order_item_id', $orderItem->id)
                    ->latest('id')
                    ->first();

                $secondLastBalance = UserProductBalance::where('user_id', $order->user_id)
                    ->where('product_id', $product->id)
                    ->orderByDesc('id')
                    ->skip(1)
                    ->take(1)
                    ->first();

                $previousRemaining = $lastBalance ? $lastBalance->remaining_units : 0;
                $newUserBalance = $previousRemaining + $deliveredUnits - $returnedUnits;

                if($secondLastBalance->remaining_units > 0) {
                    $updateUserBalance = ($secondLastBalance->remaining_units + $deliveredUnits) - $returnedUnits;
                } else {
                    $updateUserBalance = $deliveredUnits - $returnedUnits;
                }

                if ($lastBalance) {
                    $lastBalance->update([
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id,
                        'remaining_units' => $updateUserBalance,
                    ]);
                    $orderItem->remaining_customer_units = $updateUserBalance;
                } else {
                    UserProductBalance::create([
                        'user_id' => $order->user_id,
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id,
                        'remaining_units' => $newUserBalance,
                    ]);
                    $orderItem->remaining_customer_units = $newUserBalance;
                }
            }


            // --- Update order item ---
            $orderItem->update([
                'delivered_units' => $deliveredUnits,
                'returned_units' => $returnedUnits,
                'product_inventory_id' => $latestInventory->id,
                'pack_quantity' => $packQuantity,
                'remaining_stock_units' => $newInventoryRemaining,
                'unit_price' => $unitPriceFormatted,
                'total_price' => $totalPrice,
            ]);

            // Recalculate and update total order amount
            $order->update([
                'total_amount' => $order->items()->sum('total_price'),
            ]);

            // Update invoice if exists
            if ($order->getPaymentInvoice()) {
                $order->updateInvoiceTotalAmount();
            }

            return response()->json(['success' => 'Order item updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function deleteItem($orderId, $itemId)
    {
        try {
            // Find the order item
            $orderItem = OrderItem::findOrFail($itemId);
            $order = $orderItem->order; // Get the associated order
            $product = $orderItem->product; // Get the associated product

            // Update the total amount of the order
            $order->total_amount -= $orderItem->total_price;
            $order->save(); // Save the updated order

            if ($order->getPaymentInvoice()) {
                // Call updateInvoiceTotalAmount if there is a payment invoice
                $order->updateInvoiceTotalAmount();
            }

            // Update the remaining units in the product's latest confirmed inventory
            $product->latestConfirmedInventory->update([
                'remaining_units' => ($product->latestConfirmedInventory->remaining_units + $orderItem->delivered_units) - $orderItem->returned_units,
            ]);

            if ($product->type === 'pack_of_1' && $product->returnable) {
                UserProductBalance::where('user_id', $order->user_id)
                    ->where('order_id', $order->id)    
                    ->where('product_id', $product->id)
                    ->delete();
            }

            // Delete the item from the order
            $orderItem->delete();

            return response()->json(['success' => 'Product removed from the order.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function shipOrder(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'id' => 'required|exists:orders,id',
            ]);

            $order = Order::findOrFail($validated['id']);

            // Update the order status to shipped
            $order->update(['order_status' => 'shipped']);

            return response()->json(['message' => 'Order shipped successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
