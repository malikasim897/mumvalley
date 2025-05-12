<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">Customer Api Setting</h4>
            </div>
            <div class="card-body">

                <div class="row mt-2">
                    <div class="controls row mb-1 align-items-center">
                        <label class="col-md-3 text-md-right">Enable API<span class="text-danger"></span></label>
                        <div class="col-md-6">
                            <input type="checkbox" name="api_enabled" id="api_enabled"  value="{{ $user->api_enabled }}" wire:change="selectedUsers" @if( $user->api_enabled == 1 ) checked @endif> 
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="controls row mb-1 align-items-center">
                        <label class="col-md-3 text-md-right">User Token<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <textarea type="text" rows="4" class="form-control" name="token" placeholder="Token">{{  $user->api_token  }}</textarea>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="button" wire:click="revoke">Revoke</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

