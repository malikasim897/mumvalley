<div>
    @php
        use App\Enums\OrderStatusEnum;
        use Illuminate\Support\Facades\Crypt;
    @endphp
    <div class="container-fluid text-start">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-3 col-md-3">
                        <label for="">Rows</label>
                        <select id="orderPerPage" class="form-control text-start px-2" wire:model.live="orderPerPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-9 col-md-9">
                        <label for="">Search</label>
                        <input wire:model.live="search" id="search" type="text" placeholder="Search..." class="form-control mb-1" />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <label for="">From Date</label>
                        <input wire:model.live="start_date" class="form-control flatpickr-basic flatpickr-input active" name="start_date" type="text" autocomplete="off">
                    </div>
                    <div class="col-md-5">
                        <label>End Date</label>
                        <input wire:model.live="end_date" class="form-control flatpickr-basic flatpickr-input active" name="end_date" type="text" autocomplete="off">
                    </div>
                </div>
            </div>            
        </div>
    </div>
    {{-- <div class="content-header d-flex">
        <div class="content-header-left col-md-9 col-12">
            <div class="col-xl-4 col-md-6 col-12">
                <div class="my-1 mx-1">
                    <input wire:model.live="search" type="text" placeholder="Search..." class="form-control" />
                </div>
            </div>
        </div>
        @if (auth()->user()->hasRole('user'))
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 my-1 mx-1">
                    <a href="{{ route('products.create') }}"
                        class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">Create New Product</a>
                </div>
            </div>
        @endif
    </div> --}}
    <table class="table" id="parcelsTable">
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Date</th>
                {{-- @if (auth()->user()->hasRole('admin'))
                    <th>User Name</th>
                @endif --}}
                {{-- <th>WR</th> --}}
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Total Units</th>
                <th>Remaining Units</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($products->count() > 0)
                @foreach ($products as $key => $product)
                    <tr wire:key="item-{{ $product->id }}">
                        <td>
                            <img src="{{ $product->image ? asset('storage/images/products/' . $product->image) : (optional(optional($product)->latestconfirmedinventory)->product_image ? asset('storage/images/products/' . $product->latestconfirmedinventory->product_image) : asset('storage/images/products/default.png')) }}" 
                                 alt="Product Image" 
                                 class="product-img">
                        </td>                                               

                        <td>{{ \Carbon\Carbon::parse($product->created_at)->format('Y-m-d') }}</td>
                        {{-- @if (auth()->user()->hasRole('admin'))
                            <td>{{ $product->user->getPoBoxUserName() }}</td>
                        @endif --}}
                        {{-- <td>
                            <a type="button" class="text-primary">{{ $product->wr_number }}</a>
                        </td> --}}
                        <td>
                            {{ $product->unique_id }}
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ optional(optional($product)->latestconfirmedinventory)->total_units }}</td>
                        <td>{{ optional(optional($product)->latestconfirmedinventory)->remaining_units }}</td>
                        <td>
                            @php
                                $statusClass = $product->lastinventory->status 
                                    ? OrderStatusEnum::getCssClass("confirmed") 
                                    : OrderStatusEnum::getCssClass("confirmation_pending");
                                
                                $statusDescription = $product->lastinventory->status 
                                    ? OrderStatusEnum::getDescription("confirmed") 
                                    : OrderStatusEnum::getDescription("confirmation_pending");
                            @endphp
                            
                            <span class="{{ $statusClass }} me-1">{{ $statusDescription }}</span>
                        </td>                        
                        <td>
                            @canany(['product.edit', 'product.delete'])
                                <div class="dropdown" wire:key="dropdown-{{ $product->id }}" wire:ignore>
                                    @php
                                        $encryptedId = Crypt::encrypt($product->id);
                                    @endphp
                                    <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle waves-effect show-arrow"
                                        data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        {{-- @if (optional(optional($product)->latestconfirmedinventory)->remaining_units > 0 && auth()->user()->hasRole('admin'))
                                            @can('product.edit')
                                                <a class="dropdown-item" href="{{ route('product.order.units', $encryptedId) }}">
                                                    <i data-feather="shopping-cart" class="me-50"></i>
                                                    <span>Place Order</span>
                                                </a>
                                            @endcan
                                        @endif --}}
                                        {{-- @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item" href="{{ route('product.checkin', ['id' => $encryptedId]) }}">
                                                <i data-feather="box" class="me-50"></i>
                                                <span>Warehouse</span>
                                            </a>
                                        @endif --}}
                                        <a class="dropdown-item" href="{{ route('product.orders', $encryptedId) }}">
                                            <i data-feather="shopping-bag" class="me-50"></i>
                                            <span>Product Orders</span>
                                        </a>
                                        {{-- @if(auth()->user()->hasRole('admin'))
                                            <a class="dropdown-item edit-price-btn" href="#" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-unique="{{ $product->unique_id }}">
                                                <i data-feather="dollar-sign" class="me-50"></i>
                                                <span>Product Price</span>
                                            </a>
                                        @endif --}}
                                        @if(auth()->user()->hasRole('admin'))
                                            @can('product.edit')
                                                <a class="dropdown-item" href="{{ route('product.edit', ['id' => $encryptedId]) }}">
                                                    <i data-feather="box" class="me-50"></i>
                                                    <span>Inventory</span>
                                                </a>
                                            @endcan
                                        @endif
                                        @can('product.delete')
                                            <form method="POST" action="{{ route('products.destroy', $product->id) }}" id="delete-form{{ $product->id }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @if (!$product->orders()->exists())
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('delete-form{{ $product->id }}').submit();">
                                                    <i data-feather="trash" class="me-50"></i>
                                                    <span>Delete</span>
                                                </a>
                                            @endif
                                        @endcan

                                    </div>
                                </div>
                            @endcanany
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">No Record Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $products->links() }}
    @include('layouts.livewire.loading')
</div>


