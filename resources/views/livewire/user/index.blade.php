<div>
    {{-- <input wire:model.lazy="search" type="text" placeholder="Search Users..." /> --}}
   <div class="container my-1">
        <div class="row">
            <div class="col-md-3 mt-1">
                <input wire:model.live="search" type="text" placeholder="Search Users..." class="form-control" />
            </div>
            <div class="col-md-2">
                <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1 my-1 mx-1">
                        <a href="{{ route('users.create') }}"
                            class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">Add New Customer</a>
                    </div>
                </div>
            </div>
            <div class="col-md-7 text-end">
                <span class="label fw-bolder">Total Customers</span class="fw-bolder" > : {{$totalUsers - 1}} <br>
                <a href="{{route('export.user')}}" class="btn btn-sm btn-primary">Export Customers</a>
            </div>
        </div>
   </div>

    <table class="table" id="usersTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>PO BOX Number</th>
                <th>Role</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registration Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($users->count() > 0)
                @foreach ($users as $key => $user)
                    <tr wire:key="item-{{ $user->id }}">
                        <td>
                            <img src="{{ $user->image ? $user->image->url : asset('images/portrait/small/avatar-s-11.jpg') }}" 
                                 alt="User Image" 
                                 class="product-img">
                        </td>  
                        {{-- <td>{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }} </td> --}}
                        <td>{{ $user->po_box_number }}</td>
                        @foreach ($user->Roles as $key => $role)
                            <td>{{ $role->name }}</td>
                        @endforeach
                        @if ($user->setting !== null)
                            <td>{{ $user->setting->first_name . ' ' . $user->setting->last_name }}</td>
                        @else
                            <td>{{ $user->name }}</td>
                        @endif
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ optional(optional($user)->created_at)->format('d/m/Y') }}</td>
                        <td><span
                                class="{{ $user->status == 1 ? 'badge rounded-pill badge-light-success me-1' : 'badge rounded-pill badge-light-danger me-1' }}">{{ $user->status == 1 ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td>
                            @canany(['user.view', 'user.create', 'user.edit', 'user.delete'])
                                <div class="dropdown" wire:key="dropdown-{{ $user->id }}" wire:ignore>
                                    <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle waves-effect show-arrow"
                                        data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        @can('user.edit')
                                            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                <i data-feather="edit-2" class="me-50"></i>
                                                <span>Edit</span>
                                            </a>
                                        @endcan
                                        @can('user.view')
                                            <a class="dropdown-item" href="{{ route('user.admin.impersonate', $user) }}">
                                                <i data-feather="log-in" class="me-50"></i>
                                                <span>Login As</span>
                                            </a>
                                        @endcan
                                        @can('user.delete')
                                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                id="delete-user-form{{ $user->id }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <a class="dropdown-item" href="#" id="delete-user-link{{ $user->id }}"
                                                onclick="deleteUser({{ $user->id }})">
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
    {{ $users->links() }}
    @include('layouts.livewire.loading')
</div>
