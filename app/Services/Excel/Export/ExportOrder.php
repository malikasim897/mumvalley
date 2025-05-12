<?php
namespace App\Services\Excel\Export;

use Illuminate\Support\Collection;

class ExportOrder extends AbstractExportService
{
    private $orders;

    private $currentRow = 1;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;

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
        foreach ($this->orders as $order) {
            $this->setCellValue('A'.$row, $order->created_at);
            $this->setCellValue('B'.$row, $order->tracking_id);
            $this->setCellValue('C'.$row, $order->user->name);
            $this->setCellValue('D'.$row, $order->wr_number);
            $this->setCellValue('E'.$row, $order->carrier);
            $this->setCellValue('F'.$row, $order->tracking_id);
            $this->setCellValue('G'.$row, $order->tracking_code);
            $this->setCellValue('H'.$row, $order->unit);
            $this->setCellValue('I'.$row, $order->weight);
            $this->setCellValue('J'.$row, $order->length.'X'.$order->width.'X'.$order->height);
            $this->setCellValue('K'.$row, $order->service_name);
            $this->setCellValue('L'.$row, $order->status);
            $this->setColor('L'.$row, '2b5cab');
            $row++;
        }

        $this->currentRow = $row;
    }

    private function setExcelHeaderRow()
    {
        $this->setColumnWidth('A', 20);
        $this->setCellValue('A1', 'Date');

        $this->setColumnWidth('B', 20);
        $this->setCellValue('B1', 'Order ID#');

        $this->setColumnWidth('C', 20);
        $this->setCellValue('C1', 'User Name');

        $this->setColumnWidth('D', 20);
        $this->setCellValue('D1', 'WAREHOUSE#');
        
        $this->setColumnWidth('E', 20);
        $this->setCellValue('E1', 'Carrier');

        $this->setColumnWidth('F', 20);
        $this->setCellValue('F1', 'CARRIER TRACKING');

        $this->setColumnWidth('G', 20);
        $this->setCellValue('G1', 'TRACKING CODE');
        
        $this->setColumnWidth('H', 20);
        $this->setCellValue('H1', 'UNIT');
       
        $this->setColumnWidth('I', 20);
        $this->setCellValue('I1', 'Weigth');

        $this->setColumnWidth('J', 20);
        $this->setCellValue('J1', 'Dimesnsions');
        
        $this->setColumnWidth('K', 20);
        $this->setCellValue('K1', 'Servics Name');


        $this->setColumnWidth('L', 20);
        $this->setCellValue('L1', 'Status');

        $this->setBackgroundColor('A1:L1', '2b5cab');
        $this->setColor('A1:L1', 'FFFFFF');
        
        $this->currentRow++;
    }
}
