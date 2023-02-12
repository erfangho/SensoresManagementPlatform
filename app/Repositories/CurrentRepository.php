<?php

namespace App\Repositories;

use App\Interfaces\CurrentRepositoryInterface;
use App\Models\Current;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CurrentRepository implements CurrentRepositoryInterface
{
    public function getAllCurrents()
    {
        return Current::all();
    }

    public function createCurrent(Request $request)
    {
        $apiKey = $request['api_key'];

        $currentDetails = $request->only([
            'value',
            'device_id',
            'detail',
        ]);

        $device = Device::findOrFail($currentDetails['device_id']);

        if ($device['api_key'] != $apiKey) {
            return [
                'message' => 'API key is not correct',
            ];
        } else {
            $current = Current::create($currentDetails);

            $keyNameRedisDate = Carbon::createFromFormat('Y-m-d H:i:s', $current['created_at'])
                ->format('Y-m-d');

            $keyNameRedisHour = Carbon::createFromFormat('Y-m-d H:i:s', $current['created_at'])
                ->format('H:i');

            Redis::set('Currents:' . $keyNameRedisDate . ':' . $keyNameRedisHour, json_encode([
                'device_id' => $current['device_id'],
                'value' => $current['value'],
            ]));

            // TODO this response is only for stage, for production layer we should return device orders
            return [
                'Current' => $current,
                'message' => 'Current created successfully',
            ];
        }
    }

    public function getCurrentByDate($startDate, $endDate)
    {
        $currents = Current::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $currents;
    }

    public function getCurrentByDeviceId($deviceId)
    {
        $currents = Current::where('device_id', $deviceId)
            ->get();

        return $currents;
    }

    public function getCurrentByDateTime($date, $timeRange)
    {
        $time = explode('-', $timeRange);

        $startDateTime = Carbon::create($date . ' ' . $time['0'])->format("Y-m-d H:i:s");
        $endDateTime = Carbon::create($date . ' ' . $time['1'])->format("Y-m-d H:i:s");

        $currents = Current::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->get();

        return $currents;
    }
}
