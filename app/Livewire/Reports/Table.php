<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\UserProductBalance;
use Carbon\Carbon;

class Table extends Component
{
    use WithPagination;

    public $dateRange = 'today';
    public $customStartDate;
    public $customEndDate;
    public $selectedUser = '';
    public $status = '';
    public $search = '';
    public $orderPerPage = 50;

    protected $paginationTheme = 'bootstrap';

    public function updating($property)
    {
        if (in_array($property, ['dateRange', 'customStartDate', 'customEndDate', 'selectedUser', 'status', 'search'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = Order::query();

        // Date Range Filtering
        switch ($this->dateRange) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case '7days':
                $query->whereDate('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case '14days':
                $query->whereDate('created_at', '>=', Carbon::now()->subDays(14));
                break;
            case '1month':
                $query->whereDate('created_at', '>=', Carbon::now()->subMonth());
                break;
            case '6months':
                $query->whereDate('created_at', '>=', Carbon::now()->subMonths(6));
                break;
            case '1year':
                $query->whereDate('created_at', '>=', Carbon::now()->subYear());
                break;
            case 'custom':
                if ($this->customStartDate && $this->customEndDate) {
                    $query->whereBetween('created_at', [$this->customStartDate, $this->customEndDate]);
                }
                break;
        }

        // User Filtering
        if ($this->selectedUser) {
            $query->where('user_id', $this->selectedUser);
        }

        // Status Filtering
        if ($this->status) {
            $query->where('payment_status', $this->status);
        }

        // Search Filtering
        if ($this->search) {
            $query->where(function($q) {
                $q->where('date', 'like', '%' . $this->search . '%')
                    ->orWhere('total_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('order_number', 'like', '%' . $this->search . '%')
                    ->orWhere('payment_status', 'like', '%' . $this->search . '%')
                    ->orWhere('order_status', 'like', '%' . $this->search . '%')
                    ->orWhereHas('items', function ($query) {
                        $query->whereHas('product', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%'); // Search by product name
                        });
                    })
                    ->orWhereHas('items', function ($query) {
                        $query->where('delivered_units', 'like', '%' . $this->search . '%'); // Search by shipped units
                    })
                    ->orWhereHas('user', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate($this->orderPerPage);

        // Statistics
        $totalOrders = $query->count();
        $totalAmount = $query->sum('total_amount');
        $paidAmount = $query->get()->sum(function ($order) {
            return $order->getPaymentInvoice()?->paid_amount ?? 0;
        });
        $pendingAmount = $totalAmount - $paidAmount;

        // Returnable Bottles
        $returnableBottles = UserProductBalance::select('user_id', 'remaining_units')
            ->whereIn('id', function ($subQuery) {
                $subQuery->selectRaw('MAX(id)')
                         ->from('user_product_balances')
                         ->groupBy('user_id');
            })
            ->when($this->selectedUser, function ($q) {
                $q->where('user_id', $this->selectedUser);
            })
            ->sum('remaining_units');

        return view('livewire.reports.table', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'pendingAmount' => $pendingAmount,
            'returnableBottles' => $returnableBottles,
            'users' => \App\Models\User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->get(),
        ]);
    }
}
