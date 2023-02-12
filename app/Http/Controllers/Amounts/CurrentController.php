<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\AmountsRequests\StoreCurrentRequest;
use App\Http\Requests\UpdateCurrentRequest;
use App\Interfaces\CurrentRepositoryInterface;
use App\Models\Current;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CurrentController extends Controller
{
    private CurrentRepositoryInterface $currentsRepository;

    public function __construct(CurrentRepositoryInterface $currentsRepository)
    {
        $this->currentRepository= $currentsRepository;
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
        return response()->json(
            $this->currentRepository->createCurrent($request)
            , ResponseAlias::HTTP_CREATED);
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
}
