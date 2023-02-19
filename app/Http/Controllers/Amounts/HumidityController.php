<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmountsRequests\StoreHumidityRequest;
use App\Interfaces\HumidityRepositoryInterface;
use App\Models\Humidity;
use App\Models\Temperature;
use App\Services\ExportService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class HumidityController extends Controller
{
    private HumidityRepositoryInterface $humidityRepository;

    public function __construct(HumidityRepositoryInterface $humidityRepository)
    {
        $this->humidityRepository= $humidityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'Humidities' => $this->humidityRepository->getAllHumidities(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AmountsRequests\StoreHumidityRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreHumidityRequest $request)
    {
        return response()->json(
            $this->humidityRepository->createHumidity($request)
            , ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHumidityByDate($start, $end)
    {
        return response()->json([
            'Humidities' => $this->humidityRepository->getHumidityByDate($start, $end),
        ]);
    }

    public function getHumidityByDeviceId($deviceId)
    {
        return response()->json([
            'Humidities' => $this->humidityRepository->getHumidityByDeviceId($deviceId),
        ]);
    }

    public function getHumidityByDateTime($date, $timeRange)
    {
        return response()->json([
            'Humidities' => $this->humidityRepository->getHumidityByDateTime($date, $timeRange),
        ]);
    }

    public function exportHumidityAsCsv()
    {
        $temperatures = Humidity::all();

        $exportService = new ExportService();

        return $exportService->exportCsv(
            $temperatures,
            'Humidities.csv',
            ['value', 'device_id', 'detail', 'created_at']);
    }
}
