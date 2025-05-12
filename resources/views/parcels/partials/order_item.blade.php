
<div data-repeater-list="shippingItems">
    <div data-repeater-item class="orderItems">
        <div class="row d-flex justify-content-between repeater-row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="sh_code">Harmonized Code<span class="text-danger">*</span></label>
                            <select class="w-100 form-control" name="sh_code" id="sh_code" required>
                                <option label="select harmonized code" value="610190" selected disabled readonly>select harmonized code</option>
                                <option value="610190">610190</option>
                                {{-- @if ($shCodes->isNotEmpty())
                                    <select name="sh_code" id="sh_code">
                                        @foreach ($shCodes as $shcode)
                                            @php
                                                $parts = explode("-------", $shcode->description);
                                                $truncatedDescription = $parts[0];
                                            @endphp
                                            <option value="{{ $shcode->code }}">{{ $truncatedDescription }}</option>
                                        @endforeach
                                    </select>
                                @endif --}}
                            </select>
                            @foreach($errors->get('shippingItems.*.sh_code') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="description">Description<span class="text-danger">*</span></label>
                            {{-- <textarea 
                            class="form-control" id="description"
                             aria-describedby="description" name="description"  maxlength="200" cols="40" required>
                                {{ $item->description ?? old('description') }}</textarea> --}}
                            <input type="text" name="description" class="form-control" id="description" aria-describedby="description" value="{{ old('description') }}" required/>
                            @foreach($errors->get('shippingItems.*.description') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="quantity">Quantity<span class="text-danger">*</span></label>
                            <input type="number" min="1" oninput="validity.valid||(value='')" name="quantity" class="form-control quantity" id="quantity" aria-describedby="quantity" value="{{ old('quantity') }}" required/>
                            @foreach($errors->get('shippingItems.*.quantity') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="value">Value<span class="text-danger">*</span></label>
                            <input type="number" min="0.1" oninput="validity.valid" step="0.01" name="value" class="form-control value" id="value" aria-describedby="value" value="{{ old('value') }}" required/>
                            @foreach($errors->get('shippingItems.*.value') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="staticTotal">Total</label>
                            <input type="text" name="total" class="form-control staticTotal" id="staticTotal" readonly="readonly" value="0">
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="contain_goods">Is Contains restricted goods<span class="text-danger">*</span></label>
                            <select class="form-select contain_goods" name="contain_goods" id="contain_goods" required>
                                {{-- <option label="select goods"></option> --}}
                                <option value="no" selected>None</option>
                                {{-- <option value="no">No</option> --}}
                                <option value="is_battery" class="is_battery">Contain Battery</option>
                                <option value="is_perfume" class="is_perfume">Contain Perfume</option>
                                {{-- <option value="is_flameable">Contain Flameable</option> --}}
                            </select>
                            @foreach($errors->get('shippingItems.*.contain_goods') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="id" id="">
                </div>
            </div>
            <div class="col-md-2 col-12 mb-50">
                <div class="my-2">
                    <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete type="button">
                        <i data-feather="x" class="me-25"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
        <hr />
    </div>
</div>
