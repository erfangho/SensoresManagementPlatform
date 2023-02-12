<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmountsRequests\StoreTemperatureRequest;
use App\Interfaces\TemperatureRepositoryInterface;
use App\Models\Temperature;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TemperatureController extends Controller
{
    private TemperatureRepositoryInterface $temperatureRepository;

    public function __construct(TemperatureRepositoryInterface $temperatureRepository)
    {
        $this->temperatureRepository = $temperatureRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'Temperatures' => $this->temperatureRepository->getAllTemperatures(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AmountsRequests\StoreTemperatureRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTemperatureRequest $request)
    {
        return response()->json(
            $this->temperatureRepository->createTemperature($request)
            , ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemperatureByDate($start, $end)
    {
        return response()->json([
            'Temperatures' => $this->temperatureRepository->getTemperatureByDate($start, $end),
        ]);
    }

    public function getTemperatureByDeviceId($deviceId)
    {
        return response()->json([
            'Temperatures' => $this->temperatureRepository->getTemperatureByDeviceId($deviceId),
        ]);
    }

    public function getTemperatureByDateTime($date, $timeRange)
    {
        return response()->json([
            'Temperatures' => $this->temperatureRepository->getTemperatureByDateTime($date, $timeRange),
        ]);
    }
}
