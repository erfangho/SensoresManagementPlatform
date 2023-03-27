<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmountsRequests\StoreVoltageRequest;
use App\Interfaces\VoltageRepositoryInterface;
use App\Models\Voltage;
use App\Services\ExportService;
use App\Services\OrderReportService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class VoltageController extends Controller
{
    private VoltageRepositoryInterface $voltageRepository;

    public function __construct(VoltageRepositoryInterface $voltageRepository)
    {
        $this->voltageRepository = $voltageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'Voltages' => $this->voltageRepository->getAllVoltages(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AmountsRequests\StoreVoltageRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreVoltageRequest $request)
    {
        if ($this->voltageRepository->createVoltage($request)) {
            $deviceId = $request['device_id'];

            $order = new OrderReportService();
            $getStandByOrders = $order->getStandByOrders($deviceId);

            if (isset($getStandByOrders) != null) {
                $order = $getStandByOrders->getData();
            }

            return response()->json(
                $order,
                ResponseAlias::HTTP_CREATED);
        } else {
            return response()->json(
                $this->voltageRepository->createVoltage($request),
                ResponseAlias::HTTP_BAD_GATEWAY
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVoltageByDate($start, $end)
    {
        return response()->json([
            'Voltages' => $this->voltageRepository->getVoltageByDate($start, $end),
        ]);
    }

    public function getVoltageByDeviceId($deviceId)
    {
        return response()->json([
            'Voltages' => $this->voltageRepository->getVoltageByDeviceId($deviceId),
        ]);
    }

    public function getVoltageByDateTime($date, $timeRange)
    {
        return response()->json([
            'Voltages' => $this->voltageRepository->getVoltageByDateTime($date, $timeRange),
        ]);
    }

    public function getAverageVoltageByDate($start, $end)
    {
        return response()->json([
            'Voltages' => $this->voltageRepository->getAverageByDate($start, $end),
        ]);
    }
    public function exportVoltageAsCsv()
    {
        $temperatures = Voltage::all();

        $exportService = new ExportService();

        return $exportService->exportCsv(
            $temperatures,
            'voltages.csv',
            ['value', 'device_id', 'detail', 'created_at']);
    }
}
