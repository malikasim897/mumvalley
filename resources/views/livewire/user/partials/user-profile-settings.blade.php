<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">Customer Profile Settings</h4>
            </div>
            <div class="card-body">
                <!-- form -->

                <div class="row mt-2">
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="first_ame">Po Box Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="po_box_number" name="po_box_number"
                            value="{{ $user->po_box_number ?? old('po_box_number') }}"
                            data-msg="Please enter Po Box Number" placeholder="John" />
                        <x-input-error class="" :messages="$errors->get('po_box_number')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="first_ame">First Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="{{ $user->setting->first_name ?? old('first_name') }}"
                            data-msg="Please enter first name" placeholder="John" />
                        <x-input-error class="" :messages="$errors->get('first_name')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="{{ $user->setting->last_name ?? old('last_name') }}"
                            data-msg="Please enter last name" placeholder="Doe" />
                        <x-input-error class="" :messages="$errors->get('last_name')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="userEmail">Email<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" placeholder="john.doe@email.com" id="userEmail"
                            name="email" value="{{ $user->email }}" />
                        <x-input-error class="" :messages="$errors->get('email')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="userPhone">Phone<span class="text-danger">*</span> (+1234567890)</label>
                        <input type="text" class="form-control" id="userPhone" name="phone"
                            value="{{ $user->phone }}" placeholder="+1298377272" />
                        <x-input-error class="" :messages="$errors->get('phone')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" class="form-control" placeholder="98  Borough bridge Road, Birmingham"
                            id="address" name="address" value="{{ $user->setting->address ?? old('address') }}" />
                        <x-input-error class="" :messages="$errors->get('address')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="country">Country</label>
                        <select id="country" name="country_id" class="select2 form-select">
                            <option value="">Select Country</option>
                            @if ($user->setting == null)
                                @foreach ($countries as $key => $country)
                                    <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                @endforeach
                            @else
                                @foreach ($countries as $key => $country)
                                    <option value="{{ $country['id'] }}"
                                        {{ $user->setting->country_id != null && $country['id'] == $user->setting->country_id ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <x-input-error class="" :messages="$errors->get('country_id')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="state">State</label>
                        <input type="text" class="form-control" placeholder="Florida (FL)" id="state"
                            name="state" value="{{ $user->setting->state ?? old('state') }}" />
                        <x-input-error class="" :messages="$errors->get('state')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="city">City</label>
                        <input type="text" class="form-control" placeholder="Miami" id="city"
                            name="city" value="{{ $user->setting->city ?? old('city') }}" />
                        <x-input-error class="" :messages="$errors->get('city')" />
                    </div>
                    <div class="col-12 col-sm-6 mb-1">
                        <label class="form-label" for="zipcode">Zip Code</label>
                        <input type="text" class="form-control" id="zipcode" name="zipcode"
                            value="{{ $user->setting->zipcode ?? old('zipcode') }}" placeholder="5450081" />
                        <x-input-error class="" :messages="$errors->get('zipcode')" />
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-1 me-1">Save
                            changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('profile.partials.profile-js')
@include('components.loading')
