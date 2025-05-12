<div>
    <div class="content-header-left col-12">
        <div class="d-flex justify-content-between  mx-2  my-2">
            <div>
                <input wire:model.live="search" type="text" placeholder="Search..." class="form-control" />
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('rates.create') }}" class="btn btn-primary">Upload Rate</a>
            </div>
        </div>
    </div>
    <table class="table" id="ratesTable">
        <thead>
            <tr>
                <th>Id#</th>
                <th>User</th>
                <th>Shipping</th>
                <th>Sub Class</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($rates) > 0)
                @foreach ($rates as $key => $rate)
                    <tr wire:key="item-{{ $rate['id'] }}">

                        <td>{{ $rate->shipping_service_id }}</td>

                        <td>{{ optional($rate->user)->name ?? "--" }} | {{ optional($rate->user)->po_box_number ?? "--"}}</td>

                        <td>{{ $rate->shipping_service_name }}</td>
                        <td>{{ $rate->service_subclass }}</td>
                        <td>
                            <span
                                class="badge rounded-pill {{ $rate->active ? 'badge-light-success' : 'badge-light-danger' }} me-1">{{ $rate->active ? 'Active' : 'De-Active' }}</span>
                        </td>
                        <td>
                            @canany(['rate.edit', 'rate.delete'])
                                <div class="dropdown" id="dropdown" wire:key="dropdown-{{ $rate->id }}" wire:ignore>
                                    <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle waves-effect show-arrow"
                                        data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        @can('rate.view')
                                            <a class="dropdown-item" href="{{ route('rates.edit', $rate->id) }}">
                                                <i data-feather="eye" class="me-50"></i>
                                                <span>View rate</span>
                                            </a>
                                        @endcan
                                        @can('rate.edit')
                                            <a class="dropdown-item" href="{{ route('rate.change-status', $rate->id) }}">
                                                <i data-feather="printer" class="me-50"></i>
                                                <span>{{ $rate->active ? 'De-activate' : 'Activate' }}</span>
                                            </a>
                                        @endcan
                                        @can('rate.delete')
                                            <form  id="delete-user-form{{ $rate->id }}" action="{{ route('rates.destroy', $rate->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a type="submit" onclick="deleteRates({{$rate->id}})" class="dropdown-item">
                                                <i data-feather="trash" class="me-50"></i>
                                                Delete</a>
                                        @endcan
                                    </div>
                                </div>
                            @endcanany
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">No Record Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $rates->links() }}
    @include('layouts.livewire.loading')
</div>
@if (session()->has('errors'))
    @foreach (session('errors')['errors'] as $error)
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ $error }}'
            });
        </script>
    @endforeach
@endif

