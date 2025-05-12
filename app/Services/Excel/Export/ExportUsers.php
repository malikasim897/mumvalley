<?php
namespace App\Services\Excel\Export;

use Illuminate\Support\Collection;

class ExportUsers extends AbstractExportService
{
    private $users;

    private $currentRow = 1;

    public function __construct(Collection $users)
    {
        $this->users = $users;

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

        foreach ($this->users as $user) {
            $this->setCellValue('A'.$row, $user->po_box_number);
            $this->setCellValue('B'.$row, $user->name);
            $this->setCellValue('C'.$row, $user->email);
            $this->setCellValue('D'.$row, $user->phone);
            $this->setCellValue('E'.$row, $user->api_token);
            $this->setCellValue('F'.$row, $user->api_enabled);
            $row++;
        }

        $this->currentRow = $row;
    }

    private function setExcelHeaderRow()
    {
        $this->setColumnWidth('A', 20);
        $this->setCellValue('A1', 'POBOX#');

        $this->setColumnWidth('B', 20);
        $this->setCellValue('B1', 'Name');

        $this->setColumnWidth('C', 20);
        $this->setCellValue('C1', 'Email');

        $this->setColumnWidth('D', 20);
        $this->setCellValue('D1', 'Phone Number');
        
        $this->setColumnWidth('E', 20);
        $this->setCellValue('E1', 'API TOKEN');

        $this->setColumnWidth('F', 25);
        $this->setCellValue('F1', 'API ENABLE');


        $this->setBackgroundColor('A1:H1', '2b5cab');
        $this->setColor('A1:H1', 'FFFFFF');
        
        $this->currentRow++;
    }
}
