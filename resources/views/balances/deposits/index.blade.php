<x-app-layout>
    <!-- BEGIN: Content-->
    <style>
        button.btn.btn-outline-secondary.dropdown-toggle.show-arrow {
            padding: 0.4em 1.3em;
        }
    </style>
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Deposit</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('balances.index') }}">balance</a>
                                    </li>
                                    <li class="breadcrumb-item active">balance
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic Tables start -->
                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header border-bottom">
                                            <h4 class="card-title">Add Balance</h4>
                                        </div>
                                        <div class="card-body">                                            
                                            <form method="POST" action="{{ route('deposits.store') }}">
                                                @csrf
                                                <div class="row mt-2">
                                                    <div class="col-12 col-sm-4 mb-1">
                                                        <label  class="form-label" for="user_id">User</label>
                                                        <select class="select2 form-select" id="user_id" name="user_id">
                                                            @foreach ($users as $key => $user)
                                                                    <option value="{{ $user->id }}">
                                                                        {{ $user->name }}  |  {{ $user->po_box_number }} 
                                                                    </option>
                                                                @endforeach
                                                        </select>
                                                        <x-input-error class="" :messages="$errors->get('state_id')" />
                                                    </div> 

                                                    <div class="col-12 col-sm-4 mb-1">
                                                        <label class="form-label" for="user_id">Select Your Payement Method</label>
                                                        <select id="user-dd" name="is_credit" class="select2 form-select">
                                                            <option value="credit">
                                                                Credit
                                                            </option>
                                                            <option value="debit">
                                                                Debit  
                                                            </option>
                                                        </select>
                                                        <x-input-error class="" :messages="$errors->get('state_id')" />
                                                    </div> 

                                                    <div class="col-12 col-sm-4 mb-1">
                                                        <label class="form-label" for="amount">Amount<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="amount" step="0.01" name="amount"
                                                            value="{{old('amount') }}"
                                                            data-msg="Please enter last name" placeholder="Amount" />
                                                        <x-input-error class="" :messages="$errors->get('amount')" />
                                                    </div>
                                                    <div class="col-12 col-sm-4 mb-1">
                                                        <label class="form-label" for="description">Description<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="description" name="description"
                                                            value="{{old('description') }}"
                                                            data-msg="Please enter last name" placeholder="Description" />
                                                        <x-input-error class="" :messages="$errors->get('description')" />
                                                    </div> 
                                                    <div class="col-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary mt-1 me-1">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                <!-- Basic Tables end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->  
    @include('components.loading')

</x-app-layout>




