<div>
    {{-- <input wire:model.lazy="search" type="text" placeholder="Search addresss..." /> --}}
    <div class="row px-2 mt-1">
        <div class="col-xl-3 col-md-6 col-12">
            <div class="mb-1">
                <input wire:model.live="search" type="text" placeholder="Search addresss..." class="form-control" />
            </div>
        </div>
    </div>
    <table class="table" id="addressesTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                {{-- <th>Street#</th> --}}
                <th>Address</th>
                <th>Address2</th>
                {{-- <th>Account Type</th> --}}
                <th>Tax Id</th>
                <th>Zipcode</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($addresses->count() > 0)
                @foreach ($addresses as $key => $address)
                    <tr wire:key="item-{{ $address->id }}">
                        <td>{{ $address->first_name .' '. $address->last_name }}</td>
                        <td>{{ $address->email }}</td>
                        <td>{{ $address->phone }}</td>
                        <td>{{ $address->country_name }}</td>
                        <td>{{ $address->state_code }}</td>
                        <td>{{ $address->city }}</td>
                        {{-- <td>{{ $address->street_no }}</td> --}}
                        <td>{{ $address->address }}</td>
                        <td>{{ $address->address2 }}</td>
                        {{-- <td>{{ $address->account_type }}</td> --}}
                        <td>{{ $address->tax_id }}</td>
                        <td>{{ $address->zipcode }}</td>
                        <td>
                            @canany(['address.view', 'address.create', 'address.edit', 'address.delete'])
                                <div class="dropdown" wire:key="dropdown-{{ $address->id }}" wire:ignore>
                                    <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle waves-effect show-arrow"
                                        data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        @can('address.edit')
                                            <a class="dropdown-item" href="{{ route('addresses.edit', $address->id) }}">
                                                <i data-feather="edit-2" class="me-50"></i>
                                                <span>Edit</span>
                                            </a>
                                        @endcan

                                        @can('address.delete')
                                            <form method="POST" action="{{ route('addresses.destroy', $address) }}"
                                                id="delete-address-form{{ $address->id }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <a class="dropdown-item" href="#" id="delete-recipient-address{{ $address->id }}"
                                                onclick="deleteAddress({{ $address->id }})">
                                                <i data-feather="trash" class="me-50"></i>
                                                <span>Delete</span>
                                            </a>
                                            </form>
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
    {{ $addresses->links() }}
    @include('layouts.livewire.loading')
</div>
