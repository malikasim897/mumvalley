<?php

namespace App\Services\Excel\Import;

use App\Models\ShippingRate;
use App\Models\ShippingRateData;
use Illuminate\Http\UploadedFile;
use App\Services\Excel\AbstractImportService;
use App\Models\Country;
use App\Models\ShippingService;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportRates extends AbstractImportService
{
    protected $shippingServiceId;
    protected $shippingServiceName;
    protected $shippingServiceCode;
    protected $file;
    protected $userId;

    public function __construct(UploadedFile $file, $userId, $shippingServiceId, $shippingServiceName, $shippingServiceCode)
    {
        $this->shippingServiceId = $shippingServiceId;
        $this->shippingServiceName = $shippingServiceName;
        $this->shippingServiceCode = $shippingServiceCode;
        $this->userId = $userId;

        $this->file = $file;
        $filename = $this->importFile($file);
        parent::__construct(
            $this->getStoragePath($filename)
        );
    }

    public function handle()
    {
        return $this->readRatesFromFile();
    }

    public function readRatesFromFile()
    {
        $rates = [];

        $spreadsheet = IOFactory::load($this->file);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();
        $shippingRate = ShippingRate::where('shipping_service_id', $this->shippingServiceId)->where('user_id', $this->userId)->first();
        if (!$shippingRate) {
            $shippingRate = ShippingRate::create([
                'user_id' => $this->userId,
                'shipping_service_id' => $this->shippingServiceId,
                'shipping_service_name' => $this->shippingServiceName,
                'service_subclass' => $this->shippingServiceCode,
            ]);
        }

        foreach ($data as $row) {
            if ($row[0] && $row[2]) {
                $rates[] = [
                    'shipping_rate_id' => $this->shippingServiceId,
                    'weight_in_gram' => $row[0],
                    'value' => $row[2]
                ];
            }
        }
        $shippingRate->datas()->delete();
        $shippingRate->datas()->createMany($rates);
    }
}
