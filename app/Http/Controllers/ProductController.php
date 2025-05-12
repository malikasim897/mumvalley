<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\ProductCharge;

use function Symfony\Component\VarDumper\Dumper\esc;

class ProductController extends Controller
{
    protected $productRepository;
    protected $apiRepository;
    protected $addressRepository;
    protected $userRepository;


    function __construct(
        ProductRepository $productRepository,
        UserRepository $userRepository
    ) {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->middleware('permission:product.view|product.create|product.edit|product.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:product.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = $this->productRepository->getUser();
        return view('products.create', compact('users'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        // $user = $this->productRepository->findUser($request->user_id);
        $product = $this->productRepository->store($request);
        if ($product) {
            return redirect()->route('products.index')->with('success', 'product created successfully');
        } else {
            return redirect()->route('products.index')->with('error', 'product not created! Something went wrong.');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $product = $this->productRepository->getProduct($id);
        $users = $this->productRepository->getUser();

        return view('products.edit', compact('product', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = $this->productRepository->update($request, $id);
            if ($product) {
                return redirect()->back()->with('success', 'product updated successfully');
            } else {
                return redirect()->back()->with('error', 'product not updated! Something went wrong.');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('An exception occurred: ' . $errorMessage);
            return back()->with('error', 'An error occurred: ' . $errorMessage);
        }
    }

    public function dispatchUnits(Request $request)
    {
        try {
            $product = Product::find($request->product_id);
            $product = $this->productRepository->checkInUpdate($product, $request);
            if ($product) {
                return redirect()->back()->with('success', 'product stock added successfully');
            } else {
                return redirect()->back()->with('error', 'product stock not updated! Something went wrong.');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('An exception occurred: ' . $errorMessage);
            return back()->with('error', 'An error occurred: ' . $errorMessage);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->productRepository->delete($id);
        if ($product) {
            return redirect()->back()->with('success', 'product deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Product cannot be deleted because there are associated orders.');
        }
    }

    public function warehouseCheckIn($id)
    {
        $product = $this->productRepository->getProduct($id);
        $users = $this->productRepository->getUser();
        return view('products.checkin', compact('product', 'users'));
    }

    public function checkInUpdate(Request $request)
    {
        try {
            $product = $this->productRepository->checkInUpdate($request);
            if ($product) {
                return redirect()->route('products.index')->with('success', 'product updated successfully');
            } else {
                return redirect()->back()->with('error', 'product not updated! Something went wrong.');
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error('An exception occurred: ' . $errorMessage);
            return back()->with('error', 'An error occurred: ' . $errorMessage);
        }
    }

    public function unitsInfo($id)
    {
        $user = Auth::user();
        $user->load('setting');
        $products = $this->productRepository->getUserProducts();

        return view('parcels.placed.items_info', compact('products', 'user'));
    }

    public function orderDetails($id)
    {
        $users = $this->userRepository->get();
        $order = $this->productRepository->getOrder($id);
        $products = $this->productRepository->getProducts();
        
        return view('parcels.placed.edit_items_info', compact('order', 'products', 'users'));
    }

    public function invoice($id)
    {
        $user = Auth::user();
        $order = $this->productRepository->getOrder($id);
        return view('parcels.placed.order_details', compact('order', 'user'));
    }

    public function storeOrderDetails(Request $request)
    {
        try {
            // Validate the shipment label
            // $request->validate([
            //     'shipment_label' => 'required|file|mimes:pdf|max:5120', 
            // ], [
            //     'shipment_label.required' => 'The shipment label file is required.',
            //     'shipment_label.mimes' => 'The shipment label must be a PDF file.',
            //     'shipment_label.max' => 'The shipment label must not exceed 5MB.',
            // ]);

            // Validate that there are at least two products in the order
            if (count($request->products) < 2) {
                return redirect()->back()->withInput()->with('error', 'Please add products in the order.');
            }

            // Loop through the products in the request and check for latest charge
            // foreach ($request->products as $index => $productData) {
            //     if ($index === 0 || is_null($productData)) continue;

            //     $productInfo = json_decode($productData, true);
            //     $productId = $productInfo['productId'];

            //     // Find the product in the database
            //     $product = Product::find($productId);
            //     if ($product) {
            //         // Get the latest charge for the product
            //         $productCharge = $product->latestCharge;

            //         // If a product doesn't have a latest charge, return with an error
            //         if (!$productCharge) {
            //             return redirect()->back()->withInput()->with('error', "The product $product->name - $product->unique_id does not have a price.");
            //         }
            //     }
            // }

            // If all products have a latest charge, call the repository method
            $orderId = $this->productRepository->storeOrderDetails($request);

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


    public function getInvoice($id)
    {
        $order = $this->productRepository->getOrderInfo($id);
        $view = view('orders.partials.render_invoice', ['order' => $order])->render();
        return response()->json(['view' => $view]);
    }

    public function getPrice($id)
    {
        $productCharge = ProductCharge::where('product_id', $id)->latest()->first();

        // if (!$productCharge) {
        //     return response()->json(['message' => 'Product charge not found'], 404);
        // }

        if (!$productCharge) {
            return response()->json(['price' => '']);
        }

        return response()->json(['price' => $productCharge->unit_charge]);
    }

    public function updatePrice(Request $request, $id)
    {
        $product = Product::find($id);
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        // Get the latest product charge
        $productCharge = ProductCharge::where('product_id', $id)->latest()->first();

        if (!$productCharge) {
            // If no existing charge, create a new record
            $productCharge = ProductCharge::create([
                'product_id' => $id,
                'user_id' => $product->user_id,
                'unit_charge' => $request->price,
            ]);
        } else {
            // Check if the price is different
            if ($productCharge->unit_charge !== $request->price) {
                // Create a new record if the price is different
                ProductCharge::create([
                    'product_id' => $id,
                    'user_id' => $product->user_id,
                    'unit_charge' => $request->price,
                ]);
            } else {
                // Update only the updated_at field if the price is the same
                $productCharge->touch(); // Updates the updated_at field
            }
        }

        return response()->json(['message' => 'Product price updated successfully']);
    }

    public function productOrders($id) {
        // dd($id);
        $product = $this->productRepository->getProduct($id);
        // dd($product);
        return view('products.orders', compact('product'));

    }


}
