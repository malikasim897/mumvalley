<?php
namespace App\Services\Excel\Export;

use Illuminate\Support\Collection;

class ExportBalance extends AbstractExportService
{
    private $deposits;

    private $currentRow = 1;

    public function __construct(Collection $deposit)
    {
        $this->deposits = $deposit;

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

        foreach ($this->deposits as $deposit) {
            $this->setCellValue('A'.$row, $deposit->uuid);
            $this->setCellValue('B'.$row,  optional($deposit->user)->name . " | ". optional($deposit->user)->po_box_number ?? "--" );
            $this->setCellValue('C'.$row, ($deposit->is_credit?"+":"-"). $deposit->amount);
            $this->setCellValue('D'.$row, class_basename($deposit->depositable));
            $this->setCellValue('E'.$row, $deposit->balance );
            $this->setCellValue('F'.$row, $deposit->is_credit ? 'Credit':'Debit');
            $this->setCellValue('G'.$row, \Carbon\Carbon::parse($deposit->created_at)->format('Y-m-d'));
            $row++;
        }

        $this->currentRow = $row;
    }

    private function setExcelHeaderRow()
    {
        $this->setColumnWidth('A', 20);
        $this->setCellValue('A1', '#INVOICE');

        $this->setColumnWidth('B', 20);
        $this->setCellValue('B1', 'USER');

        $this->setColumnWidth('C', 20);
        $this->setCellValue('C1', 'AMOUNT');

        $this->setColumnWidth('D', 20);
        $this->setCellValue('D1', 'TYPE');

        $this->setColumnWidth('E', 20);
        $this->setCellValue('E1', 'Current');

        $this->setColumnWidth('F', 20);
        $this->setCellValue('F1', 'STATUS');

        $this->setColumnWidth('G', 25);
        $this->setCellValue('G1', 'CREATED AT');


        $this->setBackgroundColor('A1:H1', '2b5cab');
        $this->setColor('A1:H1', 'FFFFFF');

        $this->currentRow++;
    }
}
