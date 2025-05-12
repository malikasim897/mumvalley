<div class="row">
    {{-- <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">User Rates</h4>
            </div>
            <div class="card-body">
                <form class="form" action="{{ route('user.rates') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                    <div class="row mt-2">
                        <div class="col-12 col-sm-2 mb-1">
                            <label class="form-label" for="fnsku">Fnsku Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" id="fnsku_price" class="form-control" name="fnsku_price" value="{{ number_format(optional($user->latestUserRate)->fnsku_price, 2) }}" step="0.01" required oninput="limitDecimalPlaces(event)" @if (auth()->user()->hasRole('user')) readonly @endif />
                            <x-input-error :messages="$errors->get('fnsku_price')" />
                        </div>
                        <div class="col-12 col-sm-2 mb-1">
                            <label class="form-label" for="bubblewrap">Bubblewrap Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" id="bubblewrap_price" class="form-control" name="bubblewrap_price" value="{{ number_format(optional($user->latestUserRate)->bubblewrap_price, 2) }}" step="0.01" required oninput="limitDecimalPlaces(event)" @if (auth()->user()->hasRole('user')) readonly @endif />
                            <x-input-error :messages="$errors->get('bubblewrap_price')" />
                        </div>
                        <div class="col-12 col-sm-2 mb-1">
                            <label class="form-label" for="polybag">Polybag Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" id="polybag_price" class="form-control" name="polybag_price" value="{{ number_format(optional($user->latestUserRate)->polybag_price, 2) }}" step="0.01" required oninput="limitDecimalPlaces(event)" @if (auth()->user()->hasRole('user')) readonly @endif />
                            <x-input-error :messages="$errors->get('polybag_price')" />
                        </div>
                        <div class="col-12 col-sm-2 mb-1">
                            <label class="form-label" for="small_box">Small Box Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" id="small_box_price" class="form-control" name="small_box_price" value="{{ number_format(optional($user->latestUserRate)->small_box_price, 2) }}" step="0.01" required oninput="limitDecimalPlaces(event)" @if (auth()->user()->hasRole('user')) readonly @endif />
                            <x-input-error :messages="$errors->get('small_box_price')" />
                        </div>
                        <div class="col-12 col-sm-2 mb-1">
                            <label class="form-label" for="medium_box">Medium Box Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" id="medium_box_price" class="form-control" name="medium_box_price" value="{{ number_format(optional($user->latestUserRate)->medium_box_price, 2) }}" step="0.01" required oninput="limitDecimalPlaces(event)" @if (auth()->user()->hasRole('user')) readonly @endif />
                            <x-input-error :messages="$errors->get('medium_box_price')" />
                        </div>
                        <div class="col-12 col-sm-2 mb-1">
                            <label class="form-label" for="large_box">Large Box Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" id="large_box_price" class="form-control" name="large_box_price" value="{{ number_format(optional($user->latestUserRate)->large_box_price, 2) }}" step="0.01" required oninput="limitDecimalPlaces(event)" @if (auth()->user()->hasRole('user')) readonly @endif />
                            <x-input-error :messages="$errors->get('large_box_price')" />
                        </div>
                        <div class="col-12 col-sm-2 mb-1">
                            <label class="form-label" for="box">Additional Units Price (₤)<span class="text-danger">*</span></label>
                            <input type="number" id="additional_units_price" class="form-control" name="additional_units_price" value="{{ number_format(optional($user->latestUserRate)->additional_units_price, 2) }}" step="0.01" required oninput="limitDecimalPlaces(event)" @if (auth()->user()->hasRole('user')) readonly @endif />
                            <x-input-error :messages="$errors->get('additional_units_price')" />
                        </div>
                        @if (auth()->user()->hasRole('admin'))
                            <div class="col-12 content-header-right text-md-end d-md-block d-none">
                                <button type="submit" class="btn btn-primary mt-1 me-1">Save</button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
</div>

<script>
    function limitDecimalPlaces(event) {
        let value = event.target.value;
        // Limit input to two decimal places
        if (value.includes('.')) {
            let parts = value.split('.');
            if (parts[1].length > 2) {
                event.target.value = parseFloat(value).toFixed(2);
            }
        }
    }
</script>
