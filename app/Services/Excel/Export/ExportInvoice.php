<?php
namespace App\Services\Excel\Export;

use Illuminate\Support\Collection;

class ExportInvoice extends AbstractExportService
{
    private $invoices;

    private $currentRow = 1;

    public function __construct(Collection $invoices)
    {
        $this->invoices = $invoices;

        parent::__construct();
    }

    public function handle()
    {
        $this->prepareExcelSheet();

        return $this->download();
    }

    private function prepareExcelSheet()
    {
        $this->setExcelHeaderRow();

        $row = $this->currentRow;

        foreach ($this->invoices as $invoice) {
            $this->setCellValue('A'.$row, $invoice->deposit->uuid );
            $this->setCellValue('B'.$row, $invoice->user->name ?? "--");
            $this->setCellValue('C'.$row, count($invoice->orders));
            $this->setCellValue('D'.$row, $invoice->amount);
            $this->setCellValue('E'.$row, $invoice->deposit->balance );
            $this->setCellValue('F'.$row, $invoice->deposit->last_four_digits);
            $this->setCellValue('G'.$row, $invoice->deposit->is_credit ? 'Refunded' : 'Debit');
            $this->setCellValue('H'.$row, $invoice->is_paid ? 'Paid' : 'Pending');
            $this->setCellValue('I'.$row, \Carbon\Carbon::parse($invoice->created_at)->format('m/d/y'));
            $row++;
        }

        $this->currentRow = $row;
    }

    private function setExcelHeaderRow()
    {
        $this->setColumnWidth('A', 20);
        $this->setCellValue('A1', 'INVOICE#');

        $this->setColumnWidth('B', 20);
        $this->setCellValue('B1', 'USER');

        $this->setColumnWidth('C', 20);
        $this->setCellValue('C1', 'ORDER COUNT');

        $this->setColumnWidth('D', 20);
        $this->setCellValue('D1', 'AMOUNT');

        $this->setColumnWidth('E', 20);
        $this->setCellValue('E1', 'BALANCE');

        $this->setColumnWidth('F', 20);
        $this->setCellValue('F1', 'CARD LAST 4 DIGITS');

        $this->setColumnWidth('G', 20);
        $this->setCellValue('G1', 'DEPOSIT');

        $this->setColumnWidth('H', 20);
        $this->setCellValue('H1', 'STATUS');

        $this->setColumnWidth('I', 25);
        $this->setCellValue('I1', 'CREATED AT');


        $this->setBackgroundColor('A1:I1', '2b5cab');
        $this->setColor('A1:I1', 'FFFFFF');

        $this->currentRow++;
    }
}
