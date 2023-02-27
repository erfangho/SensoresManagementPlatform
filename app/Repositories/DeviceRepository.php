<?php

namespace App\Repositories;

use App\Interfaces\DeviceRepositoryInterface;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceRepository implements DeviceRepositoryInterface
{

    public function getAllDevices()
    {
        return Device::all();
    }

    public function getDevice($device)
    {
        return $device;
    }

    public function deleteDevice($device)
    {
        if ($device->deleteOrFail()) {
            return [
                'message' => 'دیوایس با موفقیت حذف شد',
            ];
        } else {
            return $device->deleteOrFail();
        }
    }

    public function createDevice(Request $request)
    {
        $deviceDetails = $request->only([
            'name',
            'phone_number',
            'sub_zone_id',
            'user_id',
            'detail',
        ]);

        $deviceDetails['api_key'] = Str::random(12);
//        dd($deviceDetails);

        return [
            'Device' => Device::create($deviceDetails),
            'message' => 'دیوایس با موفقیت ساخته شد',
        ];
    }

    public function updateDevice($device, Request $request)
    {
        $newDetails = $request->only([
            'name',
            'phone_number',
            'sub_zone_id',
            'user_id',
            'detail',
        ]);

        if ($device->update($newDetails)) {
            return [
                'Device' => $device,
                'message' => 'دیوایس با موفقیت به روزرسانی شد',
            ];
        } else {
            return $device->update($newDetails);
        }
    }
}
