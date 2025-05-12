@foreach ($parcel->orderItems as $index => $item)
    <div data-repeater-item class="orderItems">
        <div class="row d-flex justify-content-between repeater-row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="sh_code">Harmonized Code<span
                                    class="text-danger">*</span></label>
                            <select class="form-control" name="shippingItems[{{ $index }}][sh_code]" id="sh_code"
                                required>
                                <option label="harmonized code" value="610190" selected disabled readonly>select
                                    harmonized code</option>
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
                            @foreach ($errors->get('shippingItems.*.sh_code') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="description">Description<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="shippingItems[{{ $index }}][description]"
                                class="form-control" id="description" aria-describedby="description"
                                value="{{ $item->description ?? old('description') }}"
                                required /> 
                            @foreach ($errors->get('shippingItems.*.description') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="quantity">Quantity<span class="text-danger">*</span></label>
                            <input type="number" min="1" oninput="validity.valid||(value='')"
                                name="shippingItems[{{ $index }}][quantity]" class="form-control quantity"
                                id="quantity" aria-describedby="quantity"
                                value="{{ $item->quantity ?? old('quantity') }}" required />
                            @foreach ($errors->get('shippingItems.*.quantity') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="value">Value<span class="text-danger">*</span></label>
                            <input type="number" min="0.1"  step="0.01" oninput="validity.valid||(value='')"
                                name="shippingItems[{{ $index }}][value]" class="form-control value"
                                id="value" aria-describedby="value" value="{{ $item->value ?? old('value') }}"
                                required />
                            @foreach ($errors->get('shippingItems.*.value') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="staticTotal">Total</label>
                            <input type="text" name="shippingItems[{{ $index }}][total]"
                                class="form-control staticTotal" id="staticTotal" readonly="readonly"
                                value="{{ $item->total ?? 0 }}">
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="mb-1">
                            <label class="form-label" for="contain_goods">Is Contains restricted goods<span
                                    class="text-danger">*</span></label>
                            <select class="form-select contain_goods"  name="shippingItems[{{ $index }}][contain_goods]"
                                id="contain_goods" required>
                                {{-- <option value="no" selected>select goods</option> --}}
                                @foreach (['no'=> 'None', 'is_battery' => 'Contain Battery', 'is_perfume' => 'Contain Perfume'] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ $item->contain_goods == $value ? 'selected' : '' }} class="{{ $value }}">{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @foreach ($errors->get('shippingItems.*.contains_goods') as $error)
                                <x-input-error class="" :messages="$error[0]" />
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="shippingItems[{{ $index }}][id]" value="{{ $item->id }}"
                        id="">
                </div>
            </div>
            <div class="col-md-2 col-12 mb-50">
                <div class="my-2">
                    <button class="btn btn-outline-danger text-nowrap px-1 remove-item" data-repeater-delete
                        type="button" data-item-id="{{ $item->id }}">
                        <i data-feather="x" class="me-25"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
        <hr />
    </div>
@endforeach
