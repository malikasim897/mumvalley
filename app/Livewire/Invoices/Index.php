<?php

namespace App\Livewire\Invoices;

use App\Models\PaymentInvoice;
use App\Models\InvoicePayment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search;
    public $paymentInvoiceId;
    protected $paginationTheme = 'bootstrap';
    public $orderPerPage = 50;

    public function render()
    {
        $user = Auth::user();

        $query = InvoicePayment::with('user')
            ->where('payment_invoice_id', $this->paymentInvoiceId)
            ->where(function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('paid_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('remaining_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('created_at', 'like', '%' . $this->search . '%');
            });

        $payments = $query->orderBy('id', 'desc')->paginate($this->orderPerPage);
        $users = User::all();

        return view('livewire.invoices.index', compact('users', "payments"));
    }
}
