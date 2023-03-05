<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Interfaces\CurrentRepositoryInterface;
use App\Interfaces\HumidityRepositoryInterface;
use App\Interfaces\TemperatureRepositoryInterface;
use App\Interfaces\VoltageRepositoryInterface;
use App\Services\OrderReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AmountController extends Controller
{
    private TemperatureRepositoryInterface $temperatureRepository;
    private HumidityRepositoryInterface $humidityRepository;
    private CurrentRepositoryInterface $currentRepository;
    private VoltageRepositoryInterface $voltageRepository;

    public function __construct(VoltageRepositoryInterface $voltageRepository,
                                TemperatureRepositoryInterface $temperatureRepository,
                                HumidityRepositoryInterface $humidityRepository,
                                CurrentRepositoryInterface $currentRepository)
    {
        $this->voltageRepository = $voltageRepository;
        $this->currentRepository= $currentRepository;
        $this->humidityRepository= $humidityRepository;
        $this->temperatureRepository = $temperatureRepository;
    }

    public function setAllAmounts(Request $request)
    {
        $temperatureDetails = [
            'device_id' => $request['device_id'],
            'value' => $request['temperature']['value'],
            'detail' => $request['temperature']['detail'],
        ];

        $currentDetails = [
            'device_id' => $request['device_id'],
            'value' => $request['current']['value'],
            'detail' => $request['current']['detail'],
        ];

        $humidityDetails = [
            'device_id' => $request['device_id'],
            'value' => $request['humidity']['value'],
            'detail' => $request['humidity']['detail'],
        ];

        $voltageDetails = [
            'device_id' => $request['device_id'],
            'value' => $request['voltage']['value'],
            'detail' => $request['voltage']['detail'],
        ];

        try {
            $this->temperatureRepository->createTemperature(new Request($temperatureDetails));
            $this->currentRepository->createCurrent(new Request($currentDetails));
            $this->humidityRepository->createHumidity(new Request($humidityDetails));
            $this->voltageRepository->createVoltage(new Request($voltageDetails));

            $order = new OrderReportService();
            $getStandByOrders = $order->getStandByOrders($request['device_id']);

            if (isset($getStandByOrders) != null) {
                $order = $getStandByOrders->getData();
            }

            return response()->json(
                $order,
                ResponseAlias::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return response()->json(
                ['message' => 'مشکلی پیش آمده'],
                ResponseAlias::HTTP_BAD_REQUEST
            );
        }
    }
}
