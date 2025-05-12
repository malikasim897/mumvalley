<div>
    <div class="row" id="">
        <div class="col-12">
            <div class="card table-responsive">
                <div class="card-header d-flex p-1 m-0">
                    <h4>Inventory History of {{ $product->name }}</h4>
                </div>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- Pagination Dropdown -->
                    <div class="col-md-6">
                        <select wire:model="currentPageNumber" class="form-select w-auto">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <!-- Search Box -->
                    <div class="col-md-6">
                        <input wire:model.live="search" type="text" class="form-control w-auto float-end" placeholder="Search...">
                    </div>
                </div>
                <table class="table" id="parcelsTable">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Date</th>
                            <th>Product ID</th>
                            <th>Purchased Units</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($inventories->count() > 0)
                        @foreach ($inventories as $inventory)
                        <tr wire:key="item-{{ $inventory->id }}">
                            <td>
                                <img src="{{ $inventory->product_image ? asset('storage/images/products/' . $inventory->product_image) : (optional($inventory)->product->image ? asset('storage/images/products/' . $inventory->product->image) : asset('storage/images/products/default.png')) }}" 
                                     alt="Product Image" 
                                     class="product-img">
                            </td>  
                            <td><span class="badge rounded-pill badge-light-info me-1">{{ \Carbon\Carbon::parse($inventory->date)->format('Y-m-d') }}</span></td>
                            <td>{{ $inventory->product->unique_id }}</td>
                            <td>{{ $inventory->purchased_units }}</td>
                            <td>{{ $inventory->unit_price }}</td>
                            <td>{{ $inventory->total_price }}</td>
                            <td>
                                @php
                                    $statusClass = $inventory->status 
                                        ? \App\Enums\OrderStatusEnum::getCssClass("confirmed") 
                                        : \App\Enums\OrderStatusEnum::getCssClass("confirmation_pending");
                                    
                                    $statusDescription = $inventory->status 
                                        ? \App\Enums\OrderStatusEnum::getDescription("confirmed") 
                                        : \App\Enums\OrderStatusEnum::getDescription("confirmation_pending");
                                @endphp
                                <span class="{{ $statusClass }} me-1">{{ $statusDescription }}</span>{{ $inventory->remaining_units }}
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="text-center">No Record Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
    
                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $inventories->links() }}
                    @include('layouts.livewire.loading')
                </div>
            </div>
        </div>
    </div>
</div>
