<?php

namespace App\Livewire\PaymentInvoice;

use Exception;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $orderPerPage = 25;
    public $amount;
    public $delivered_units;
    public $order_number;
    public $sortBy = 'id';
    public $sortAsc = false;
    public $selectedOrder;
    public $selectedOrders = [];
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->selectedOrder = request('order');
        if ($this->selectedOrder) {
            try {
                $this->selectedOrder = decrypt($this->selectedOrder);
                $this->selectedOrders[] = $this->selectedOrder;
            } catch (Exception $e) {
                $this->selectedOrder = null;
            }
        }
    }

    public function render()
    {
        return view('livewire.payment-invoice.create', [
            'orders' => $this->getUnpaidOrders(),
            'selectedOrders' => $this->selectedOrders
        ]);
    }

    public function getUnpaidOrders()
    {
        $query = Order::query()
            ->with('items')
            ->where(function ($query) {
                // Check if selectedOrders are not empty
                if (!empty($this->selectedOrders)) {
                    $query->whereIn('user_id', function ($subquery) {
                        $subquery->select('user_id')
                            ->from('orders')
                            ->whereIn('id', $this->selectedOrders);
                    });
                } else {
                    // Fallback to current user's ID if no selected orders
                    $query->where('user_id', Auth::id());
                }
            })
            ->where('payment_status', false)
            ->where('order_status', 'in_process')
            ->where('total_amount', '>', 0)
            ->doesntHave('paymentInvoices')
            ->with('items.product')
            ->orderBy('id', 'desc');

        $query->when($this->delivered_units, function ($query) {
            $query->whereHas('items', function ($subquery) {
                $subquery->select('order_id')
                    ->having(DB::raw('SUM(delivered_units)'), '>=', $this->delivered_units);
            });
        });

        $query->when($this->order_number, function ($query) {
            $query->where('order_number', 'like', '%' . $this->order_number . '%');
        });

        $query->when($this->amount, function ($query) {
            $query->where('total_amount', 'like', '%' . $this->amount . '%');
        });

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('date', [$this->start_date, $this->end_date]);
        }

        return $query->paginate($this->orderPerPage);
    }

    public function toggleOrderSelection($orderId)
    {
        if (in_array($orderId, $this->selectedOrders)) {
            $this->selectedOrders = array_diff($this->selectedOrders, [$orderId]);
        } else {
            $this->selectedOrders[] = $orderId;
        }
    }
}
