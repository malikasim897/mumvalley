<?php

namespace App\Livewire\Transactions;

use App\Models\Order;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use App\Models\PaymentInvoice;
use Illuminate\Support\Facades\Auth;

class index extends Component
{
    use WithPagination; // Use the WithPagination trait

    public $search;
    protected $paginationTheme = 'bootstrap';
    public $start_date;
    public $end_date;
    public $status = 'all';

    public $orderPerPage=50;

    public function render()
    {
        $user = Auth::user();
        
        // Build the query for transactions and their paymentInvoices relationship
        $query = Transaction::with(['user', 'paymentInvoices', 'storageInvoices'])
            ->where(function ($query) {
                $query->Where('payment_type', 'like', '%' . $this->search . '%')
                    ->orWhere('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('latest_charge_id', 'like', '%' . $this->search . '%')
                    ->orWhere('transaction_date', 'like', '%' . $this->search . '%')
                    ->orWhereHas('paymentInvoices', function($query){
                        $query->where('uuid', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('storageInvoices', function($query){
                        $query->where('uuid', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('user', function($query){
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        
        // Restrict query if user has a specific role
        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }

        // Apply date filter
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
        }

        // Apply status filter only if status is not 'all'
        $query->when($this->status !== 'all', function ($query) {
            $query->where('status', $this->status);
        });

        // Get paginated orders
        $transactions = $query->orderBy('id', 'desc')->paginate($this->orderPerPage);
        

        return view('livewire.transactions.index', compact('transactions'));
    }

}
