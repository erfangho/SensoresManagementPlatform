<?php

namespace App\Interfaces;

use App\Models\Device;
use Illuminate\Http\Request;

interface DeviceRepositoryInterface
{
    public function getAllDevices();
    public function getDevice(Device $device);
    public function deleteDevice(Device $device);
    public function createDevice(Request $request);
    public function updateDevice(Device $device, Request $request);
}
