<?php

namespace App\Http\Controllers\Zone;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubZoneRequests\StoreSubZoneRequest;
use App\Http\Requests\SubZoneRequests\UpdateSubZoneRequest;
use App\Interfaces\SubZoneRepositoryInterface;
use App\Models\SubZone;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SubZoneController extends Controller
{
    private SubZoneRepositoryInterface $subZoneRepository;

    public function __construct(SubZoneRepositoryInterface $subZoneRepository)
    {
        $this->subZoneRepository = $subZoneRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'subZones' => $this->subZoneRepository->getAllSubZones(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SubZoneRequests\StoreSubZoneRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSubZoneRequest $request)
    {
        $user = auth()->user();

        return response()->json(
            $this->subZoneRepository->createSubZone($request, $user)
            , ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubZone  $subZone
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SubZone $subZone)
    {
        return response()->json([
            'subZone' => $this->subZoneRepository->getSubZone($subZone),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubZone  $subZone
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(SubZone $subZone)
    {
        return response()->json($subZone);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SubZoneRequests\UpdateSubZoneRequest  $request
     * @param  \App\Models\SubZone  $subZone
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSubZoneRequest $request, SubZone $subZone)
    {

        $user = auth()->user();

        if ($user['role_id'] == 1 or $subZone['user_id'] == $user['id']) {
            return response()->json(
                $this->subZoneRepository->updateSubZone($subZone, $request)
                , ResponseAlias::HTTP_OK);
        } else {
            return response()->json(
                ['message' => 'شما دسترسی لازم برای ایجاد تغییر در این زیر ناحیه را ندارید']
                , ResponseAlias::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubZone  $subZone
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SubZone $subZone)
    {
        return response()->json(
            $this->subZoneRepository->deleteSubZone($subZone)
            , ResponseAlias::HTTP_OK);
    }
}
