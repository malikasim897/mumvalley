<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Image;
use App\Models\Order;
use App\Models\Address;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Recipient;
use Illuminate\Support\Str;
use App\Models\InvoiceOrder;
use App\Models\ShippingRate;
use App\Models\ProductInventory;
use App\Models\UserProductBalance;
use Illuminate\Support\Facades\DB;
use App\Models\OrderCustomerDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\View\Components\Warn;
use Illuminate\Contracts\Auth\Authenticatable;

class ProductRepository
{

    public function __construct() 
    {
        //
    }

    public function getUser()
    {
        return User::all();
    }

    public function findUser($id)
    {
        return User::findOrFail($id);
    }


    public function store($request)
    {
        $product = Product::create([
            'user_id' => 1,
            'name' => $request->product_name,
            'type' => $request->type,
            'returnable' => $request->has('returnable')
        ]);

        if (!$product) {
            return false;
        }

        // Generate the product unique id using the newly created product's ID
        $product->unique_id = 'P' . str_pad($product->id, 2, '0', STR_PAD_LEFT) . 'MV';
        $product->save();

        $this->addProductStock($product, $request);

        return true;
    }

    public function addProductStock($product, $request) {

        $date = Carbon::now()->toDateString();

        if ($request->hasFile('product_image')) {
            $storagePath = 'public/images/products';
            $productImage = $this->uploadImage($product, $request, $storagePath);
            $product->update(['image' => $productImage]);
        } else {
            $productImage = $product->image;
        }

        

        ProductInventory::create([
            'product_id' => $product->id,
            'product_image' => $productImage,
            'purchased_units' => $request->purchased_units,
            'total_units' => $request->purchased_units,
            'remaining_units' => $request->purchased_units,
            'unit_price' => $request->unit_price,
            'total_price' => ($request->unit_price) * ($request->purchased_units),
            'date' => $date,
            'status' => true,
        ]);

        return true;
    }


    public function getProduct($id)
    {
        $pId = Crypt::decrypt($id);
        return Product::find($pId);
    }

    public function getUserProducts()
    {
        return Product::where('user_id', Auth::user()->id)
            ->whereHas('latestConfirmedInventory', function ($query) {
                // Fetch the latest confirmed inventory with remaining units > 0
                $query->where('remaining_units', '>', 0);
            })
            ->get();
    }

    public function getProducts()
    {
        return Product::whereHas('latestConfirmedInventory', function ($query) {
                // Fetch the latest confirmed inventory with remaining units > 0
                $query->where('remaining_units', '>', 0);
            })
            ->get();
    }

    public function getOrderUserProducts($order)
    {
        return Product::where('user_id', $order->user_id)
            ->whereHas('latestConfirmedInventory', function ($query) {
                // Fetch the latest confirmed inventory with remaining units > 0
                $query->where('remaining_units', '>', 0);
            })
            ->get();
    }

    public function getOrder($id)
    {
        $orderId = Crypt::decrypt($id);
        return Order::with('paymentInvoices')->find($orderId);
    }

    public function getOrderInfo($id)
    {
        return Order::with('paymentInvoices')->find($id);
    }

    public function getOrderProduct($id)
    {
        $orderId = Crypt::decrypt($id);
        $order =  Order::with('paymentInvoices')->find($orderId);

        return Product::with('charges')->find($order->product_id);

    }

    public function update($request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return false;
        }

        $product->update([
            'product_name' => $request->product_name,
        ]);

        $this->updateProductStock($product, $request);

        return true;
    }

    public function updateProductStock($product, $request) {

        if ($request->hasFile('product_image')) {
            $storagePath = 'public/images/products';
            $this->updateProductImage($product, $request, $storagePath);
        }

        $product->lastinventory->update(['purchased_units' => $request->dispatched_units]);
    }

    public function checkInUpdate($product, $request)
    {  
        if (!$product) {
            return false;
        }
        
        $latestConfirmedInventory = $product->latestConfirmedInventory;

        $remainingUnits = optional($latestConfirmedInventory)->remaining_units ?? 0;
        $totalUnits = optional($latestConfirmedInventory)->total_units ?? 0;

        $date = Carbon::now()->toDateString();

        if ($request->hasFile('product_image')) {
            $storagePath = 'public/images/products';
            $productImage = $this->uploadImage($product, $request, $storagePath);
            $product->update(['image' => $productImage]);
        } else {
            $productImage = $product->image;
        }

        ProductInventory::create([
            'product_id' => $product->id,
            'product_image' => $productImage,
            'purchased_units' => $request->purchased_units,
            'total_units' => $request->purchased_units + $totalUnits,
            'remaining_units' => $request->purchased_units + $remainingUnits,
            'unit_price' => $request->unit_price,
            'total_price' => ($request->unit_price) * ($request->purchased_units),
            'date' => $date,
            'status' => true,
        ]);     

        return true;
    }


    public function delete($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->orders()->exists()) {
            return false;
        }

        $storagePath = 'public/images/products';
        $imagePath = $storagePath . '/' . $product->product_image;

        if ($product->product_image && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        return $product->delete();
    }

    public function uploadImage(Product $product, $request, $storagePath)
    {
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $uniqueName = Str::random(32) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs($storagePath, $uniqueName);

            return $uniqueName;
        }

        return null;
    }

    public function updateProductImage($product, $request, $storagePath)
    {
        if ($request->hasFile('product_image')) {
            $oldImageName = $product->lastinventory->product_image;
            $oldImagePath = $storagePath . '/' . $oldImageName;
            // Check if the old image exists and delete it
            if ($oldImageName && Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            $image = $request->file('product_image');
            $uniqueName = Str::random(32) . '.' . $image->getClientOriginalExtension();

            // Store the new image and update the product
            $imagePath = $image->storeAs($storagePath, $uniqueName);
            $product->lastinventory->update(['product_image' => $uniqueName]);

            return $imagePath;
        }

        return null;
    }

    public function updateProductWarehouseImage($product, $request, $storagePath)
    {
        if ($request->hasFile('warehouse_image')) {
            $oldImageName = $product->lastinventory->warehouse_image;
            $oldImagePath = $storagePath . '/' . $oldImageName;
            // Check if the old image exists and delete it
            if ($oldImageName && Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            $image = $request->file('warehouse_image');
            $uniqueName = Str::random(32) . '.' . $image->getClientOriginalExtension();

            // Store the new image and update the product
            $imagePath = $image->storeAs($storagePath, $uniqueName);
            $product->lastinventory->update(['warehouse_image' => $uniqueName]);

            return $imagePath;
        }

        return null;
    }


    public function getWrNumber()
    {
        $oldSaleOrder = Order::first();
        $maxId = Order::max('id');
        $newId = 'SL' . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
        $newId = substr($newId, 0, 8);
        return $oldSaleOrder !== null ? $newId : 'SL0001';
    }

    public function storeOrderDetails($request)
    {
        // $user = User::find($request->user_id);
        $totalAmount = 0.00; // Initialize total amount
        $packQuantity = 0;

        // Create new order
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => $totalAmount, // Set the total amount
            'date' => now(),
        ]);

        // Loop through products starting from index 1
        foreach ($request->products as $index => $productData) {
            if ($index === 0 || is_null($productData)) continue; // Skip the first index or null entries

            $productInfo = json_decode($productData, true);
            $productId = $productInfo['productId'];
            $deliveredUnits = $productInfo['shippedUnits'];
            $returnedUnits = $productInfo['returnedUnits'];

            $product = Product::find($productId);
            if ($product) {

                $unitPrice = $productInfo['unitPrice']; 
                $totalPrice = $unitPrice * $deliveredUnits;

                // Format prices to two decimal places
                $unitPrice = number_format($unitPrice, 2, '.', '');
                $totalPrice = number_format($totalPrice, 2, '.', '');

                if ($product->type === 'pack_of_6') {
                    $deliveredUnits = $deliveredUnits * 6;
                    $packQuantity = $productInfo['shippedUnits'];
                }

                if ($product->type === 'pack_of_12') {
                    $deliveredUnits = $deliveredUnits * 12;
                    $packQuantity = $productInfo['shippedUnits'];
                }

                // Create the order item
                $orderItem = $order->items()->create([
                    'product_id' => $productId,
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
                        ->where('product_id', $productId)
                        ->latest('id')
                        ->first();
                
                    $previousRemaining = $lastBalance ? $lastBalance->remaining_units : 0;
                    $newRemaining = ($previousRemaining + $deliveredUnits) - $returnedUnits;
                
                    UserProductBalance::create([
                        'user_id' => $request->user_id,
                        'product_id' => $productId,
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id,
                        'remaining_units' => $newRemaining
                    ]);
                
                    // Update order item as well with snapshot
                    $orderItem->update(['remaining_customer_units' => $newRemaining]);
                }
                

                // Accumulate the total amount for the order
                $totalAmount += (float)$totalPrice;

                // Update remaining units in inventory
                $product->latestConfirmedInventory->update([
                    'remaining_units' => ($product->latestConfirmedInventory->remaining_units - $deliveredUnits) + $returnedUnits,
                ]);
            }
        }

        // Update the total amount in the order after processing all items
        $order->update(['total_amount' => number_format($totalAmount, 2, '.', '')]);

        $orderId = $order->id; // Get the newly created order ID

        // Generate the warehouse number
        $orderNumber = 'MV' . 
            str_pad(substr($orderId, 0, 3), 3, '0', STR_PAD_LEFT) .  // First 3 digits of the order ID
            str_pad(substr(date('s'), 0, 2), 2, '0', STR_PAD_LEFT) .   // First 2 digits of the seconds
            str_pad($orderId % 10000, 4, '0', STR_PAD_LEFT) .    // Last 4 digits of the order ID, if order ID is less than 10000, it will pad to ensure 4 digits
            'PK';  // Fixed suffix

        // Update the order with the generated order number
        $order->update(['order_number' => $orderNumber]);

        return $orderId; // Return the newly created order ID
    }

    public function uploadLabelFile(Order $order, $request, $storagePath)
    {
        if ($request->hasFile('shipment_label')) {
            $file = $request->file('shipment_label');
            $uniqueName = Str::random(32) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($storagePath, $uniqueName);

            return $uniqueName;
        }

        return null; // No file uploaded
    }


    public function getSenderDetails()
    {
        return Product::query()->where("user_id",auth()->user()->id)->get();
    }

}
