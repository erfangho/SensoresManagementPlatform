<?php

namespace App\Services;

use App\Models\Current;
use App\Models\Humidity;
use App\Models\Temperature;
use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;

class DeviceOnlineStatusService
{
    public function getStatusFromCreatedTime($devices)
    {
        $now = Carbon::now();
        $tenMinutesAgo = $now->subMinutes(11);

        $onlineDevices = array();
        $offlineDevices = array();

        foreach ($devices as $device) {
            $temperature = Temperature::where('device_id', $device['id'])
                ->where('created_at', '>=', $tenMinutesAgo)
                ->get()->toArray();

            $humidity = Humidity::where('device_id', $device['id'])
                ->where('created_at', '>=', $tenMinutesAgo)
                ->get()->toArray();

            $current = Current::where('device_id', $device['id'])
                ->where('created_at', '>=', $tenMinutesAgo)
                ->get()->toArray();

            if (isset($temperature['0']) or isset($humidity['0']) or isset($current['0'])) {
                $onlineDevices += ([
                    $device['name'] => 'online',
                ]);
            } else {
                $offlineDevices += ([
                    $device['name'] => 'offline',
                ]);
            }
        }

        return [
            'online' => count($onlineDevices),
            'offline' => count($offlineDevices),
            'devices' => [
                'online' => array_keys($onlineDevices),
                'offline' => array_keys($offlineDevices),
            ]
        ];
    }
}
