<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">Customer Role Settings</h4>
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="role">Roles</label>
                        <select class="form-select" id="role" name="role">
                            <option value="{{ $user->getRoleNames()->first() }}">{{ $user->getRoleNames()->first() }}</option>
                            @foreach ($roles as $role)
                                @if ($role->name !== $user->getRoleNames()->first())
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error class="" :messages="$errors->get('role')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="{{ $user->status == 1 ? 1 : 0 }}">
                                {{ $user->status == 1 ? 'Active' : 'Inactive' }}</option>
                            @if ($user->status === 1)
                                <option value = 0>Inactive</option>
                            @endif
                            @if ($user->status === 0)
                                <option value = 1>Active</option>
                            @endif
                        </select>
                        <x-input-error class="" :messages="$errors->get('status')" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
