<?php

namespace App\Livewire\PaymentInvoice;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PaymentInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Invoice; // Make sure to import your Invoice model

class EditInvoice extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $orderPerPage = 25;
    public $invoiceId;
    public $invoice;
    public $selectedOrders = [];
    public $amount;
    public $shipped_units;
    public $warehouse_number;
    public $start_date;
    public $end_date;
    public $user_name;


    protected $rules = [
        'invoice.warehouse_number' => 'required|string|max:255',
        'invoice.amount' => 'required|numeric',
        'invoice.status' => 'required|string',
    ];

    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        $this->invoice = PaymentInvoice::findOrFail($this->invoiceId);
        $this->selectedOrders = $this->invoice->orders->pluck('id')->toArray();
    }

    public function updateInvoice()
    {
        $this->validate();

        $this->invoice->save();

        // Sync selected orders (if any)
        $this->invoice->orders()->sync($this->selectedOrders);

        session()->flash('message', 'Invoice updated successfully!');
        return redirect()->route('invoices.index'); // Redirect to the invoice list page
    }

    public function toggleOrderSelection($orderId)
    {
        if (in_array($orderId, $this->selectedOrders)) {
            $this->selectedOrders = array_diff($this->selectedOrders, [$orderId]);
        } else {
            $this->selectedOrders[] = $orderId;
        }
    }

    public function render()
    {
        return view('livewire.payment-invoice.edit-invoice', [
            'orders' => $this->getUnpaidOrders() // Fetch all orders, adjust as necessary
        ]);
    }

    public function getUnpaidOrders()
    {
        $user = Auth::user();

        $query = Order::query()
            ->with('items')
            ->with('user')
            ->where('payment_status', 'unpaid')
            ->where('order_status', 'in_process')
            ->where('total_amount', '>', 0)
            ->doesntHave('paymentInvoices')
            ->with('items.product')
            ->orderBy('id', 'desc');

        $query->when($this->shipped_units, function ($query) {
            $query->whereHas('items', function ($subquery) {
                $subquery->select('order_id')
                    ->having(DB::raw('SUM(delivered_units)'), '>=', $this->shipped_units);
            });
        });

        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }

        $query->when($this->warehouse_number, function ($query) {
            $query->where('order_number', 'like', '%' . $this->warehouse_number . '%');
        });

        $query->when($this->amount, function ($query) {
            $query->where('total_amount', 'like', '%' . $this->amount . '%');
        });

        $query->when($this->user_name, function ($query) {
            $query->whereHas('user', function ($subquery) {
                $subquery->where('name', 'like', '%' . $this->user_name . '%');
            });
        });       

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        }

        return $query->paginate($this->orderPerPage);
    }
}
