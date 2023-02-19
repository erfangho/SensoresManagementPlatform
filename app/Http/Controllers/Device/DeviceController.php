<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceRequests\StoreDeviceRequest;
use App\Http\Requests\DeviceRequests\UpdateDeviceRequest;
use App\Interfaces\DeviceRepositoryInterface;
use App\Models\Device;
use App\Services\DeviceOnlineStatusService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DeviceController extends Controller
{
    private DeviceRepositoryInterface $deviceRepository;

    public function __construct(DeviceRepositoryInterface $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'Devices' => $this->deviceRepository->getAllDevices(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\DeviceRequests\StoreDeviceRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDeviceRequest $request)
    {
        return response()->json(
            $this->deviceRepository->createDevice($request)
            , ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Device $device)
    {
        return response()->json([
            'Device' => $this->deviceRepository->getDevice($device),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Device $device)
    {
        return response()->json($device);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DeviceRequests\UpdateDeviceRequest  $request
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        return response()->json(
            $this->deviceRepository->updateDevice($device, $request)
            , ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Device $device)
    {
        return response()->json(
            $this->deviceRepository->deleteDevice($device)
            , ResponseAlias::HTTP_OK);
    }

    public function getDevicesStatus()
    {
        $devices = Device::all();

        $deviceOnlineStatusService = new DeviceOnlineStatusService();

        return response()->json(
            $deviceOnlineStatusService->getStatusFromCreatedTime($devices),
            ResponseAlias::HTTP_OK
        );
    }
}
