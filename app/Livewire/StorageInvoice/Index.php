<?php

namespace App\Livewire\StorageInvoice;

use App\Repositories\StorageInvoiceRepository;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Pagination theme
    protected $paginationTheme = 'bootstrap';

    // Public properties
    public $pageSize = 30;
    public $user;
    public $uuid;
    public $month;
    public $count;
    public $amount;
    public $type;
    public $is_paid;
    public $start_date;
    public $end_date;
    public $totalInvoices = 0;
    public $paidInvoices = 0;
    public $unpaidInvoices = 0;

    public $sortBy = 'id';
    public $sortAsc = false;

    public function mount()
    {
        // Set default values for start_date and end_date
        // $this->start_date = date('Y-m-01');
        // $this->end_date = date('Y-m-d');
    }

    // Render the component
    public function render()
    {
        return view('livewire.storage-invoice.index', [
            'invoices' => $this->getInvoices() // Retrieve the invoices
        ]);
    }

    // Method to handle sorting by column name
    public function sortBy($name)
    {
        if ($name == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortBy = $name;
            $this->sortAsc = true;
        }
    }

    // Get the filtered, paginated invoices and update totals
    public function getInvoices()
    {
        // Use an array to pass parameters correctly
        $parameters = [
            'user' => $this->user,
            'uuid' => $this->uuid,
            'product_count' => $this->count,
            'total_amount' => $this->amount,
            'month' => $this->month,
            'payment_type' => $this->type,
            'is_paid' => $this->is_paid,
            'start_date' => $this->start_date, // Start date filter
            'end_date' => $this->end_date,     // End date filter
            'pageSize' => $this->pageSize,
            'sortBy' => $this->sortBy,
            'sortAsc' => $this->sortAsc ? 'asc' : 'desc',
        ];

        // Call the repository to get the invoices and totals
        $result = (new StorageInvoiceRepository)->get($parameters, true, $this->pageSize, $this->sortBy, $this->sortAsc ? 'asc' : 'desc');

        // Set totals from the result
        $this->totalInvoices = $result['totalInvoices'];
        $this->paidInvoices = $result['paidInvoices'];
        $this->unpaidInvoices = $result['unpaidInvoices'];

        // Return the invoices data
        return $result['invoices'];
    }

    // Reset pagination when any filter is updated
    public function updating($propertyName)
    {
        $this->resetPage();
    }
}
