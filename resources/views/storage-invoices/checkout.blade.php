<x-app-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif
        }

        p {
            margin: 0
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background-color: #e8eaf6;
            padding: 35px;
        }

        .box-right {
            padding: 30px 25px;
            background-color: white;
            border-radius: 15px
        }

        .box-left {
            padding: 20px 20px;
            background-color: white;
            border-radius: 15px
        }

        .textmuted {
            color: #7a7a7a
        }

        .bg-green {
            background-color: #d4f8f2;
            color: #06e67a;
            padding: 3px 0;
            display: inline;
            border-radius: 25px;
            font-size: 11px
        }

        .p-blue {
            font-size: 14px;
            color: #1976d2
        }

        .fas.fa-circle {
            font-size: 12px
        }

        .p-org {
            font-size: 14px;
            color: #fbc02d
        }

        .h7 {
            font-size: 15px
        }

        .h8 {
            font-size: 12px
        }

        .h9 {
            font-size: 10px
        }

        .bg-blue {
            background-color: #dfe9fc9c;
            border-radius: 5px
        }

        .form-control {
            box-shadow: none !important
        }

        .card input::placeholder {
            font-size: 14px
        }

        ::placeholder {
            font-size: 14px
        }

        input.card {
            position: relative
        }

        .far.fa-credit-card {
            position: absolute;
            top: 10px;
            padding: 0 15px
        }

        .fas,
        .far {
            cursor: pointer
        }

        .cursor {
            cursor: pointer
        }

        .btn.btn-primary {
            box-shadow: none;
        }

        .bg.btn.btn-primary {
            background-color: transparent;
            border: none;
            color: #1976d2
        }

        .bg.btn.btn-primary:hover {
            color: #539ee9
        }

        @media(max-width:320px) {
            .h8 {
                font-size: 11px
            }

            .h7 {
                font-size: 13px
            }

            ::placeholder {
                font-size: 10px
            }
        }

        /* Hide the default checkbox */
        input[type="checkbox"] {
            display: none;
        }

        /* Custom checkbox appearance */
        .checkbox-wrapper {
            position: relative;
            display: inline-block;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            background-color: #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
            position: relative;
        }

        /* Checkmark indicator */
        .custom-checkbox::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background-color: #7367F0;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: all 0.3s ease;
        }

        /* Spin animation when checked */
        input[type="checkbox"]:checked + .custom-checkbox {
            animation: spin 0.6s ease;
            background-color: #7367F0;
        }

        input[type="checkbox"]:checked + .custom-checkbox::after {
            transform: translate(-50%, -50%) scale(1);
        }

        /* Spin keyframes */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        /* Blue outline on focus */
        .StripeElement--focus {
            border-color: #80BDFF;
            outline:0;
            box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        /* Can't see what I type without this */
        #card-number.form-control,
        #card-cvc.form-control,
        #card-exp.form-control {
            display:inline-block;
        }
    </style>
    <script src="https://js.stripe.com/v3/"></script>
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Orders</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                                    <li class="breadcrumb-item active">Payment</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-lg p-1">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Storage Invoice Checkout ({{ $invoice->uuid }}) Month ({{ $invoice->charge_month }})</h4>
                                    <a href="{{ route('storage-invoices.index') }}" class="btn btn-primary">Back to List</a>
                                </div>
                                <hr class="mt-0 mb-0">
                                <div class="card-body">
                                        <div class="row m-0">
                                            <div class="col-md-7 col-12">
                                                <div class="row">
                                                    <div class="col-12 mb-4">
                                                        <div class="row box-right bg-light">
                                                            <div class="col-md-8 ps-0">
                                                                <p class="ps-3 textmuted fw-bold h6 mb-1">TOTAL INVOICE AMOUNT</p>
                                                                <p class="h1 ps-3 fw-bold d-flex"><span class="textmuted h6 align-text-top ">₤</span>{{ number_format($invoice->total_amount, 2) }}</p>
                                                                <p class="ms-3 px-2 bg-green">{{ str_pad(count($invoice->products), 2, '0', STR_PAD_LEFT) }} products in invoice </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p class="textmuted h6 mb-1">PAY THIS INVOICE VIA</p>
                                                                <div class="checkbox-wrapper d-flex align-items-center mb-2">
                                                                    <input class="form-check-input me-1" type="radio" name="payment_method" id="bank_radio" value="bank">
                                                                    <label for="bank_radio">
                                                                        <span class="badge rounded-pill badge-light-dark" me-1 style="font-size:11px;">
                                                                            <i data-feather="briefcase" class="me-50"></i>
                                                                            BANK TRANSFER
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox-wrapper d-flex align-items-center">
                                                                    <input class="form-check-input me-1" type="radio" name="payment_method" id="card_radio" value="card">
                                                                    <label for="card_radio">
                                                                        <span class="badge rounded-pill badge-light-dark" me-1 style="font-size:11px;">
                                                                            <i data-feather="credit-card" class="me-50"></i>
                                                                            USE CARD
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Bank Transfer Section -->
                                                    <div class="col-12 px-0" id="bank_section" style="display: none;">
                                                        <form action="{{ route('storageInvoiceCheckout.uploadReceipt') }}" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                                            @csrf
                                                            <div class="box-right bg-light p-2">
                                                                <div class="d-flex mb-2">
                                                                    <p class="text-dark px-2 bg-light-info">Upload Bank Payment Receipt to Complete Orders</p>
                                                                </div>
                                                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                                                    <div class="bank-details">
                                                                        <p><strong>Bank Transfer Instructions:</strong></p>
                                                                        <p>Account Name: Your Company Name</p>
                                                                        <p>Bank Name: XYZ Bank</p>
                                                                        <p>Account Number: 123456789</p>
                                                                        <p>IBAN: ABCD1234</p>
                                                                        <p>SWIFT/BIC: XYZBANKXX</p>
                                                                    </div>
                                                                    <div class="bank-logo text-right">
                                                                        <img src="{{ asset('images/logo/bank.png') }}" style="width: 150px;" alt="bank" class="img-fluid">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <div class="col-12 mb-1">
                                                                        <p class="mb-1"><strong>Upload Bank Receipt:</strong></p>
                                                                        <input type="file" name="bankReceipt" class="form-control" id="bankReceipt" accept="image/*" required>
                                                                    </div>
                                                                    <div class="col-12 text-end">
                                                                        <button type="submit" class="btn btn-success p-blue h8">
                                                                            <i data-feather="briefcase" class="me-50"></i> Pay Invoice
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                
                                                    <!-- Card Payment Section -->
                                                    <div class="col-12 px-0" id="card_section" style="display: none;">
                                                        <div class="box-right bg-light p-2">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <div>
                                                                    <p class=" mb-1"><strong>Invoice Payment:</strong></p>
                                                                    <p class="text-muted h8 mb-2">Make payment for this invoice by filling in the details</p>
                                                                </div>
                                                                <img src="{{ asset('images/logo/stripe.png') }}" style="width: 150px;" alt="Stripe" class="img-fluid">
                                                            </div>
                                                            <div class="">
                                                                <div id="card-errors" role="alert"></div>

                                                                <!-- Payment Form -->
                                                                <form id="payment-form">
                                                                    <!-- Hidden field to store the amount -->
                                                                    <input type="hidden" name="amount" id="amount" value="{{$invoice->total_amount}}"> <!-- For example, £5.00 -->
                                                                    <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice->uuid}}">
                                                                    <input type="hidden" name="user_name" id="user_name" value="{{$invoice->user->name}}">
                                                                    <input type="hidden" name="user_email" id="user_email" value="{{$invoice->user->email}}">

                                                                    <!-- Payment Element placeholder -->
                                                                    <div id="payment-element"></div> <!-- Stripe will inject card fields here -->

                                                                    <!-- Submit button -->
                                                                    <button id="submit-button" class="btn btn-primary w-100 mt-3">Pay (₤ {{ number_format($invoice->total_amount, 2)}})</button>
                                                                </form>

                                                                <!-- Payment status area for success or error messages -->
                                                                <div id="payment-status" class="mt-3 text-danger"></div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5 col-12 ps-md-5 p-0">
                                                <div class="box-left bg-light">
                                                    <p class="textmuted h8">Invoice# {{$invoice->uuid}}</p>
                                                    <p class="fw-bold h7 text-dark"><strong>{{$invoice->user->name}}</strong></p>
                                                    <p class="textmuted h8">{{optional(optional(optional($invoice)->user)->setting)->address}}, {{optional(optional(optional($invoice)->user)->setting)->city}}</p>
                                                    <p class="textmuted h8 mb-2">{{optional(optional(optional($invoice)->user)->setting)->state}}, {{optional(optional(optional($invoice)->user)->country)->name}} {{optional(optional(optional($invoice)->user)->setting)->zipcode}}</p>
                                                    <div class="h8 bg-blue p-1">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <tr class="mb-2" style="border: solid 1px;">
                                                                    <th class="text-dark">Product</th>
                                                                    <th class="text-dark">Percentage Shipped</th>
                                                                    <th class="text-dark">Charges</th>
                                                                </tr>
                                                                <tbody>
                                                                    @foreach($invoice->products as $product)
                                                                        <tr class="mb-2" style="border: solid 1px;">
                                                                            <td>
                                                                                <a type="button" class="text-primary">{{ $product->product->name }} - {{ $product->product->unique_id }}</a>
                                                                            </td>
                                                                            <td>{{ $product->shipped_percentage }}</td>
                                                                            <td>{{ $product->storage_charges }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr class="mb-2" style="border: solid 1px;">
                                                                        <th colspan="2" class="text-dark">Total Invoice Amount</th>
                                                                        <th class="text-dark">₤ {{ number_format($invoice->total_amount, 2) }}</th>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bankRadio = document.getElementById('bank_radio');
            const cardRadio = document.getElementById('card_radio');
            const bankSection = document.getElementById('bank_section');
            const cardSection = document.getElementById('card_section');
    
            const resetForms = () => {
                // Clear bank receipt input
                document.getElementById('bankReceipt').value = '';
    
                // Clear card payment form fields
                //document.getElementById('name').value = '';
                // Add clear logic for Stripe fields if needed
            };
    
            bankRadio.addEventListener('change', function () {
                if (bankRadio.checked) {
                    bankSection.style.display = 'block';
                    cardSection.style.display = 'none';
                    resetForms(); // Clear previous inputs
                }
            });
    
            cardRadio.addEventListener('change', function () {
                if (cardRadio.checked) {
                    cardSection.style.display = 'block';
                    bankSection.style.display = 'none';
                    resetForms(); // Clear previous inputs
                }
            });
        });
    </script>

    <script>
        // Initialize Stripe.js with your publishable key
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        let isStripeInitialized = false; // Flag to track if Stripe is already initialized
        let elements, paymentElement; // Keep track of elements and paymentElement

        // Function to create a payment intent and mount the Payment Element
        async function initializeStripe() {
            const amount = document.getElementById("amount").value;
            const name = document.getElementById("user_name").value;
            const id = document.getElementById("invoice_id").value;
            const email = document.getElementById("user_email").value;

            if (!isStripeInitialized) {
                try {
                    // Fetch the payment intent from the server
                    const response = await fetch("/create-storage-invoice-payment-intent", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token for security
                        },
                        body: JSON.stringify({ amount: amount, name: name, id: id, email: email }) // Send amount dynamically
                    });

                    const data = await response.json();

                    // Check if there was an error in fetching the clientSecret
                    if (!data.clientSecret) {
                        throw new Error("Failed to create PaymentIntent.");
                    }

                    const clientSecret = data.clientSecret;

                    // Initialize the elements group with the clientSecret
                    elements = stripe.elements({ clientSecret });

                    // Create and mount the Payment Element
                    paymentElement = elements.create("payment");
                    paymentElement.mount("#payment-element");

                    // Mark as initialized
                    isStripeInitialized = true;
                } catch (error) {
                    // Handle any errors that occur during fetch or payment processing
                    document.getElementById("payment-status").textContent = "Error: " + error.message;
                }
            }
        }

        // Handle form submission
        async function handleStripeSubmit(event) {
            event.preventDefault(); // Prevent default form submission

            try {
                // Confirm the payment when the form is submitted
                const { error } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        // Set the return URL where the user will be redirected after payment
                        return_url: "{{ route('storage-invoices.process') }}", // Redirect after success
                    }
                });

                if (error) {
                    // Show error message in the UI
                    document.getElementById("payment-status").textContent = error.message;
                } else if (paymentIntent) {
                    // Payment succeeded, no further action needed on this page
                    document.getElementById("payment-status").textContent = "Payment processing...";
                }
            } catch (error) {
                document.getElementById("payment-status").textContent = "Error: " + error.message;
            }
        }

        // Listen for radio button change
        document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                if (this.value === 'card') {
                    // Only initialize Stripe if it hasn't been initialized yet
                    if (!isStripeInitialized) {
                        initializeStripe(); // Call the Stripe function if card is selected
                    } else {
                        // If Stripe is already initialized, remount the payment element
                        paymentElement.mount("#payment-element");
                    }
                } 
                // else {
                //     // Optionally, remove Stripe elements if switching back to bank transfer
                //     if (isStripeInitialized && paymentElement) {
                //         paymentElement.unmount();
                //     }
                // }
            });
        });

        // Initialize Stripe Elements when the page is first loaded if needed
        window.addEventListener('DOMContentLoaded', (event) => {
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPaymentMethod && selectedPaymentMethod.value === 'card') {
                initializeStripe();
            }
        });

        // Attach the form submit event handler
        const form = document.getElementById("payment-form");
        form.addEventListener("submit", handleStripeSubmit);
    </script>

</x-app-layout>
