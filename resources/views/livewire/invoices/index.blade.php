{{-- <style>
    #invoicesTable.show {
        display: block;
        height: 100vh;
    }
</style> --}}
<div>
    <div class="content-header d-flex justify-content-between mb-1">
        <div class="col-md-1  mx-1 mt-1">
            <select id="orderPerPage" class="form-control text-start px-2" wire:model.live="orderPerPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="content-header-left">
            <div class="">
                <div class="my-1 mx-1">
                    <input wire:model.live="search" type="text" placeholder="Search..." class="form-control" />
                </div>
            </div>
        </div>
    </div>
    <table class="table" id="invoicesTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Paid Amount (Rs.)</th>
                <th>Balance (Rs.)</th>
                <th>Payment Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if ($payments->count() > 0)
                @foreach ($payments as $key => $payment)
                    <tr wire:key="item-{{ $payment->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payment->paid_amount }}</td>
                        <td>{{ $payment->remaining_amount }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y') }}</td>
                        <td>
                            <span
                                class="badge rounded-pill {{ $payment->paymentInvoice->is_paid ? 'badge-light-success' : 'badge-light-danger' }} me-1">{{ $payment->paymentInvoice->is_partial ? 'Paid' : 'Partial Payment' }}</span>
                        </td>
                        {{-- <td>
                            @canany(['order.edit', 'order.delete'])
                                <div class="dropdown" id="dropdown" wire:key="dropdown-{{ $invoice->id }}" wire:ignore>
                                    <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle waves-effect show-arrow"
                                        data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        @can('order.view')
                                            <a class="dropdown-item " href="{{ route('invoices.show', $invoice->id) }}">
                                                <i data-feather="eye" class="me-50"></i>
                                                <span>View Invoice</span>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endcanany
                        </td> --}}
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">No Record Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{ $payments->links() }}
    @include('layouts.livewire.loading')
</div>
@if (session()->has('errors'))
    @foreach (session('errors')['errors'] as $error)
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ $error }}'
            });
        </script>
    @endforeach
@endif
