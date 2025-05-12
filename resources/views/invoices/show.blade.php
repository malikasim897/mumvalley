<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->paymentInvoices->first()->uuid }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            border: 4px solid #ccc;
            padding: 15px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 6px;
            border: 1px solid #000;
            text-align: center;
        }

        th {
            background-color: #D2EEFD;
        }

        .no-border {
            border: none;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .logo {
            max-width: 180px;
        }

        .section-title {
            font-size: 16px;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    @php $invoice = $order->paymentInvoices->first(); @endphp

    <!-- Header with Logo and Invoice Info -->
    <table class="no-border" style="margin-bottom: 20px;">
        <tr>
            <td class="no-border text-left" style="width: 60%;">
                <img src="{{ public_path('images/logo/logo1.png') }}" alt="Mum Valley Logo" class="logo">
            </td>
            <td class="no-border text-right" style="width: 40%;">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('m/d/y') }}</p>
                <p><strong>Invoice #:</strong> {{ $invoice->uuid }}</p>
            </td>
        </tr>
    </table>

    <!-- Company & Customer Info Side-by-Side -->
    <table class="no-border" style="margin-bottom: 20px;">
        <tr>
            <td class="no-border text-left" style="width: 71%;">
                <p><strong>Mum Valley</strong><br>
                Kucha Khuh Road, Near Abdul Hakim <br>
                Bypass Abdul Hakim, 58180<br>+92 330 0006860<br>
                mumvalley@account.com</p>
            </td>
            <td class="no-border text-left" style="width: 29%;">
                <p><strong>Customer:</strong><br>
                {{ optional($order->user)->name }} {{ optional($order->user)->last_name }} - {{ optional($order->user)->po_box_number }}<br>
                {{ optional(optional(optional($order)->user)->setting)->address }},
                {{ optional(optional(optional($order)->user)->setting)->zipcode }},
                {{ optional(optional(optional($order)->user)->country)->iso2 }}<br>
                {{ optional($order->user)->phone }}<br>
                {{ optional($order->user)->email }}</p>
            </td>
        </tr>
    </table>

    <!-- Order Info -->
    <p class="section-title">Order Details</p>
    <table>
        <thead>
            <tr>
                <th>Order No.</th>
                <th>Payment Status</th>
                <th>Order Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>
                    @if($order->payment_status === 'unpaid')
                        <span class="badge rounded-pill badge-light-warning me-1">Unpaid</span>
                    @elseif($order->payment_status === 'partial_paid')
                        <span class="badge rounded-pill badge-light-primary me-1">Partial Paid</span>
                    @elseif($order->payment_status === 'paid')
                        <span class="badge rounded-pill badge-light-success me-1">Paid</span>
                    @elseif($order->payment_status === 'cancelled')
                        <span class="badge rounded-pill badge-light-danger me-1">Cancelled</span>
                    @endif
                </td>
                <td>
                    @switch($order->order_status)
                        @case('delivered') Delivered @break
                        @case('cancelled') Cancelled @break
                        @case('in_process') In Process @break
                        @case('completed') completed @break
                        @default Unknown
                    @endswitch
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Order Items -->
    <p class="section-title">Order Items</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left">Item</th>
                <th>Delivered Units</th>
                <th>Returned Units</th>
                <th>Remaining Units</th>
                <th>Unit Price (Rs.)</th>
                <th>Total (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $item->product->name }}</td>
                    <td>
                        {{ $item->product->type === 'pack_of_6' ? $item->delivered_units / 6 :
                           ($item->product->type === 'pack_of_12' ? $item->delivered_units / 12 :
                           $item->delivered_units) }}
                    </td>
                    <td>{{ $item->returned_units }}</td>
                    <td>{{ $item->remaining_customer_units }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td>{{ $item->total_price }}</td>
                </tr>
            @endforeach

            <!-- Paid Amount Row -->
            <tr>
                <td colspan="6" class="text-right"><strong>Paid Amount:</strong></td>
                <td>{{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
            </tr>

            <!-- Remaining Amount Row -->
            <tr>
                <td colspan="6" class="text-right"><strong>Remaining Amount:</strong></td>
                <td>{{ number_format($invoice->differnceAmount(), 2) }}</td>
            </tr>

            <!-- Total Invoice Amount Row -->
            <tr>
                <td colspan="6" class="text-right" style="border-top: 2px solid;"><strong>Total Invoice Amount (Rs.):</strong></td>
                <td style="border-top: 2px solid;"><strong>{{ number_format($invoice->orders()->sum('total_amount'), 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <!-- Footer Note -->
    <p class="text-center" style="margin-top: 40px;"><em>This is a computer generated invoice and does not require any signature.</em></p>

</body>
</html>
