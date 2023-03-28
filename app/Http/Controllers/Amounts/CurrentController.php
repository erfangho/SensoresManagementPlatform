<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmountsRequests\StoreCurrentRequest;
use App\Interfaces\CurrentRepositoryInterface;
use App\Models\Current;
use App\Models\Order;
use App\Models\Temperature;
use App\Services\ExportService;
use App\Services\OrderReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CurrentController extends Controller
{
    private CurrentRepositoryInterface $currentRepository;

    public function __construct(CurrentRepositoryInterface $currentRepository)
    {
        $this->currentRepository= $currentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'Currents' => $this->currentRepository->getAllCurrents(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AmountsRequests\StoreCurrentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCurrentRequest $request)
    {
        if ($this->currentRepository->createCurrent($request)) {
            $deviceId = $request['device_id'];

            $order = new OrderReportService();
            $getStandByOrders = $order->getStandByOrders($deviceId);

            if (isset($getStandByOrders) != null) {
                $order = $getStandByOrders->getData();
            }

            return response()->json(
                $order,
                ResponseAlias::HTTP_CREATED
            );
        } else {
            return response()->json(
                $this->currentRepository->createCurrent($request),
                ResponseAlias::HTTP_BAD_GATEWAY
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentByDate($start, $end)
    {
        return response()->json([
            'Currents' => $this->currentRepository->getCurrentByDate($start, $end),
        ]);
    }

    public function getCurrentByDeviceId($deviceId)
    {
        return response()->json([
            'Currents' => $this->currentRepository->getCurrentByDeviceId($deviceId),
        ]);
    }

    public function getCurrentByDateTime($date, $timeRange)
    {
        return response()->json([
            'Currents' => $this->currentRepository->getCurrentByDateTime($date, $timeRange),
        ]);
    }

    public function getAverageCurrentByDate(Request $request, $start, $end)
    {
        return response()->json([
            'Currents' => $this->currentRepository->getAverageByDate($request, $start, $end),
        ]);
    }

    public function exportCurrentAsCsv()
    {
        $temperatures = Current::all();

        $exportService = new ExportService();

        return $exportService->exportCsv(
            $temperatures,
            'currents.csv',
            ['value', 'device_id', 'detail', 'created_at']);
    }
}
