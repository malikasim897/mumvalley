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
            background-color: #dcf5f1;
            color: #33c506;
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
            background-color: #eaecf3;
            color: #2b94da;
            padding: 3px 0;
            display: inline;
            border-radius: 25px;
            font-size: 11px
        }

        .bg-red {
            background-color: #eed3e6;
            color: #da2b65;
            padding: 3px 0;
            display: inline;
            border-radius: 25px;
            font-size: 11px
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
                                    <h4 class="mb-0">Order Invoice Checkout <a class="getInvoice text-primary" type="button" id="getInvoice" data-id={{ $invoice->orders->first()->id }}>({{$invoice->uuid}})</a></h4>
                                    <a href="{{ route('payment-invoices.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                                </div>
                                <hr class="mt-0 mb-0">
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12 mb-4">
                                                        <div class="row box-right bg-light">
                                                            <div class="col-md-4 ps-0">
                                                                <p class="ps-3 textmuted fw-bold h6 mb-1"><b>TOTAL INVOICE AMOUNT</b></p>
                                                                <p class="h1 ps-3 fw-bold d-flex"><span class="textmuted h6 align-text-top ">PKR</span>{{ number_format($invoice->total_amount, 2) }}</p>
                                                                <p class="ms-3 px-2 bg-green">{{ str_pad($invoice->order_count, 2, '0', STR_PAD_LEFT) }} Orders in Invoice</p>
                                                            </div>
                                                            <div class="col-md-4 ps-0">
                                                                <p class="ps-3 textmuted fw-bold h6 mb-1"><b>TOTAL PAID AMOUNT</b></p>
                                                                <p class="h1 ps-3 fw-bold d-flex"><span class="textmuted h6 align-text-top ">PKR</span>{{ number_format($invoice->paid_amount, 2) }}</p>
                                                                @if ($invoice->invoicePayments->isNotEmpty())
                                                                    @php
                                                                        $lastPayment = $invoice->invoicePayments->sortByDesc('created_at')->first();
                                                                    @endphp
                                                                    <p class="ms-3 px-2 bg-blue">
                                                                        Last Payment On: {{ $lastPayment->created_at->format('d-m-Y') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-4 ps-0">
                                                                <p class="ps-3 textmuted fw-bold h6 mb-1"><b>TOTAL REMAINING AMOUNT</b></p>
                                                                <p class="h1 ps-3 fw-bold d-flex"><span class="textmuted h6 align-text-top ">PKR</span>{{ number_format($invoice->differnceAmount(), 2) }}</p>
                                                                @if($invoice->partial_paid)
                                                                    <p class="ms-3 px-2 bg-red">Invoice Partially Paid</p>
                                                                @elseif($invoice->is_paid)
                                                                    <p class="ms-3 px-2 bg-red">Invoice Paid</p>
                                                                @else
                                                                    <p class="ms-3 px-2 bg-red">Payment Pending</p>
                                                                @endif
                                                            </div>
                                                            {{-- <div class="col-md-4">
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
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                
                                                    <!-- Bank Transfer Section -->
                                                    <div class="col-12 px-0" id="payment_section">
                                                        <form action="{{ route('checkout.addPayment') }}" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                                            @csrf
                                                            <div class="box-right bg-light p-2">
                                                                <div class="form-group row mt-1">
                                                                    <div class="col-3 mb-1">
                                                                        <p class="mb-1"><strong>Enter Payment Amount:</strong></p>
                                                                        <input type="number" name="amount" class="form-control" id="amount" required>
                                                                    </div>
                                                                    <div class="col-3 mb-1">
                                                                        <p class="mb-1"><strong>Remaining Amount:</strong></p>
                                                                        <input type="number" name="remaining_amount" class="form-control" value="{{ $invoice->differnceAmount() }}" id="remaining_amount" readonly>
                                                                    </div>
                                                                    <div class="col-4 mb-1">
                                                                        <p class="mb-2"><strong>Payment Type:</strong></p>
                                                                        <label class="checkbox-wrapper">
                                                                            <input class="mt-5" type="checkbox" name="partial_payment" id="partial_payment" >
                                                                            <span class="custom-checkbox"></span>&nbsp;&nbsp;Partial&nbsp;
                                                                        </label>
                                                                        
                                                                        <label class="checkbox-wrapper">
                                                                            
                                                                            <input type="checkbox" name="complete_payment" id="complete_payment" >
                                                                            <span class="custom-checkbox"></span>&nbsp;&nbsp;Complete
                                                                        </label>
                                                                    </div>
                                                                    
                                                                    <div class="col-12 text-end">
                                                                        <button type="submit" class="btn btn-success p-blue h8">
                                                                            <i data-feather="briefcase" class="me-50"></i> Add Payment
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div class="col-12 px-0 mt-4">
                                                        <div class="box-right bg-light p-2">
                                                            <div class="card-header pb-0"><h4>List of Payments</h4></div>
                                                            <div class="table-responsive">
                                                                @livewire('invoices.index', ['paymentInvoiceId' => $invoice->id])
                                                            </div>
                                                        </div>
                                                    </div>

                                                
                                                    <!-- Card Payment Section -->
                                                    {{-- <div class="col-12 px-0" id="card_section" style="display: none;">
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
                                                    </div> --}}
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
    <!-- View Order Modal -->
    <div class="modal fade text-start" id="viewOrderModal" tabindex="-1" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalData"></div>
                </div>
            </div>
        </div>
    </div>
    @include('orders.partials.render_invoice_js')
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
                    const response = await fetch("/create-payment-intent", {
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
                        return_url: "{{ route('payment-invoices.process') }}", // Redirect after success
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const amountInput = document.getElementById("amount");
        const remainingInput = document.getElementById("remaining_amount");
        const partialCheckbox = document.getElementById("partial_payment");
        const completeCheckbox = document.getElementById("complete_payment");
        const originalRemaining = parseFloat(remainingInput.value) || 0;

        // Clear checkboxes on page load
        partialCheckbox.checked = false;
        completeCheckbox.checked = false;

        amountInput.addEventListener("input", function () {
            const enteredAmount = parseFloat(this.value);

            // Reset checkboxes
            partialCheckbox.checked = false;
            completeCheckbox.checked = false;

            if (!enteredAmount || enteredAmount <= 0) {
                // If no amount entered or 0, reset remaining
                remainingInput.value = originalRemaining.toFixed(2);
                return;
            }

            const updatedRemaining = originalRemaining - enteredAmount;
            remainingInput.value = (updatedRemaining < 0 ? 0 : updatedRemaining).toFixed(2);

            if (updatedRemaining > 1) {
                partialCheckbox.checked = true;
            } else if (updatedRemaining <= 0) {
                completeCheckbox.checked = true;
            }
        });
    });
</script>


</x-app-layout>
