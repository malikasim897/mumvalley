<div>
    <div class="content-header d-flex">
        <div class="content-header-left col-md-9 col-12">
            <div class="col-xl-4 col-md-6 col-12">
                <div class="my-1 mx-1">
                    <input wire:model.live="search" type="text" placeholder="Search..." class="form-control" />
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            <div class="mb-1 my-1 mx-1">
                <a href="{{ route('roles.create') }}"
                    class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">Create</a>
            </div>
        </div>
    </div>
    <table class="table" id="rolesTable">
        <thead>
            <tr>
                <th width="100px">No</th>
                <th>Name</th>
                <th width="100px" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($roles->count() > 0)
                @php
                    $i = 1;
                @endphp
                @foreach ($roles as $key => $role)
                    <tr wire:key="item-{{ $role->id }}">
                        <td>{{ $i++ }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @canany(['role.edit', 'role.delete'])
                                <div class="d-flex rounded-md" role="group">
                                    @can('role.edit')
                                        <a class="d-flex btn btn-icon btn-gradient-primary me-75"
                                            href="{{ route('roles.edit', $role->id) }}">
                                            <i data-feather="edit-2"></i>
                                            {{-- <span>Edit</span> --}}
                                        </a>
                                    @endcan
                                    @can('role.edit')
                                        <a class="d-flex btn btn-gradient-secondary btn-icon  me-75"
                                            href="{{ route('roles.edit.permissions', $role->id) }}">
                                            <i data-feather="key"></i>
                                            {{-- <span>Permission</span> --}}
                                        </a>
                                    @endcan
                                    @can('role.delete')
                                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                            id="delete-form{{ $role->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <a class="d-flex btn btn-gradient-danger btn-icon" href="#"
                                            id="delete-role-link{{ $role->id }}" onclick="deleteRole({{ $role->id }})">
                                            <i data-feather="trash"></i>
                                            {{-- <span>Delete</span> --}}
                                        </a>
                                        </form>
                                    @endcan
                                </div>
                                {{-- <div class="d-flex flex-column text-start text-lg-end">
                                    <div class="d-flex">
                                        @can('role.edit')
                                            <a class="btn btn-gradient-primary btn-sm me-75"
                                                href="{{ route('roles.edit', $role->id) }}">
                                                <span>Edit</span>
                                            </a>
                                        @endcan
                                        @can('role.edit')
                                            <a class="btn btn-gradient-secondary btn-sm me-75"
                                                href="{{ route('roles.edit.permissions', $role->id) }}">
                                                Permission
                                            </a>
                                        @endcan
                                        @can('role.delete')
                                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                                id="delete-form{{ $role->id }}" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <a class="btn btn-gradient-danger btn-sm" href="#"
                                                id="delete-role-link{{ $role->id }}"
                                                onclick="deleteRole({{ $role->id }})">
                                                Delete
                                            </a>
                                            </form>
                                        @endcan
                                    </div>
                                </div> --}}
                            @endcanany
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center">No Record Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $roles->links() }}
    @include('layouts.livewire.loading')
</div>
