<tr class="
    {{ $invoice->cancelled ? 'bg-light-danger' : '' }}
    {{ $invoice->is_paid ? 'bg-light-success' : ($invoice->partial_paid ? 'bg-light-info' : '') }}
">
    @php
    use App\Enums\OrderStatusEnum;
    use Illuminate\Support\Facades\Crypt;
    @endphp
    
    <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}</td>
    <td><a type="button" class="viewInvoice text-primary" id="viewInvoice" data-id={{ $invoice->id }}>{{ $invoice->uuid }}</a></td>
    @if(auth()->user()->hasRole('admin'))
        <td>{{ optional($invoice->user)->name }}</td>
    @endif
    <td>
        {{ round($invoice->orders()->count(),2) }}
    </td>
    <td>
        {{ $invoice->orders()->sum('total_amount') }}
    </td>

    <td>
        @if ($invoice->isPaid() )
            <span class="badge rounded-pill badge-light-success" me-1>
                Paid
            </span>
        @elseif($invoice->cancelled)
            <span class="badge rounded-pill badge-light-danger" me-1>
                Cancelled
            </span>
        @elseif($invoice->partial_paid)
            <span class="badge rounded-pill badge-light-primary" me-1>
                Partial Paid
            </span>
        @else
            <span class="badge rounded-pill badge-light-warning" me-1>
                Payment Pending
            </span>
        @endif
    </td>
    
    <td>
        <div class="dropdown" id="dropdown" wire:key="dropdown-{{ $invoice->id }}" wire:ignore>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle waves-effect show-arrow" data-bs-toggle="dropdown">
                Action
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                
                <a class="dropdown-item" href="{{ route('payment-invoices.invoice.show', Crypt::encrypt($invoice->id)) }}" title="View Invoice">
                    <i data-feather="eye" class="me-50"></i><span>View Invoice</span>
                </a> 

                {{-- @if (auth()->user()->hasRole('admin') && !$invoice->isPaid())
                    <a class="dropdown-item" href="{{ route('payment-invoices.invoice.edit', Crypt::encrypt($invoice->id)) }}" title="Edit Invoice">
                        <i data-feather="edit" class="me-50"></i><span>Edit</span>
                    </a>
                @endif --}}

                @if ($invoice->payment_receipt)
                    <a class="dropdown-item" href="{{ asset('storage/receipts/' . $invoice->payment_receipt) }}" target="_blank">
                        <i data-feather="file" class="me-50"></i><span>View Receipt</span>
                    </a>
                @endif

                @if (!$invoice->isPaid())
                    <a class="dropdown-item" href="{{ route('payment-invoices.invoice.checkout.index', Crypt::encrypt($invoice->id)) }}" title="Pay Invoice">
                        <i data-feather="dollar-sign" class="me-50"></i><span>Pay</span>
                    </a>
                @endif
                
                @if (auth()->user()->hasRole('admin') && !$invoice->isPaid())
                    <a class="dropdown-item" href="#" id="delete-invoice{{ $invoice->id }}" onclick="showDeleteConfirmation('{{ $invoice->id }}')">
                        <i data-feather="trash" class="me-50"></i>
                        <span>Delete</span>
                    </a>
                @endif

            </div>
        </div>
    </td>
</tr>