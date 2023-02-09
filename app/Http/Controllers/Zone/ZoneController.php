<?php

namespace App\Http\Controllers\Zone;

use App\Http\Controllers\Controller;
use App\Http\Requests\ZoneRequests\StoreZoneRequest;
use App\Http\Requests\ZoneRequests\UpdateZoneRequest;
use App\Interfaces\ZoneRepositoryInterface;
use App\Models\Zone;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ZoneController extends Controller
{
    private ZoneRepositoryInterface $zoneRepository;

    public function __construct(ZoneRepositoryInterface $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'zones' => $this->zoneRepository->getAllZones(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ZoneRequests\StoreZoneRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreZoneRequest $request)
    {
        return response()->json(
            $this->zoneRepository->createZone($request)
            , ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Zone $zone)
    {
        return response()->json([
            'zone' => $this->zoneRepository->getZone($zone),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Zone $zone)
    {
        return response()->json($zone);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ZoneRequests\UpdateZoneRequest  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        return response()->json(
            $this->zoneRepository->updateZone($zone, $request)
            , ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Zone $zone)
    {
        return response()->json(
            $this->zoneRepository->deleteZone($zone)
            , ResponseAlias::HTTP_OK);
    }
}
