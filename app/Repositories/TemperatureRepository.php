<?php

namespace App\Repositories;

use App\Interfaces\TemperatureRepositoryInterface;
use App\Models\Device;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TemperatureRepository implements TemperatureRepositoryInterface
{
    public function getAllTemperatures()
    {
        return Temperature::all();
    }

    public function createTemperature(Request $request)
    {
        $temperatureDetails = $request->only([
            'value',
            'device_id',
            'detail',
        ]);

        $temperature = Temperature::create($temperatureDetails);

        $keyNameRedisDate = Carbon::createFromFormat('Y-m-d H:i:s', $temperature['created_at'])
            ->format('Y-m-d');

        $keyNameRedisHour = Carbon::createFromFormat('Y-m-d H:i:s', $temperature['created_at'])
            ->format('H:i');

        Redis::set('Tepmerature:' . $keyNameRedisDate . ':' . $keyNameRedisHour, json_encode([
            'device_id' => $temperature['device_id'],
            'value' => $temperature['value'],
        ]));

        // TODO this response is only for stage, for production layer we should return device orders
        return [
            'Temperature' => $temperature,
            'message' => 'Temperature created successfully',
        ];
    }

    public function getTemperatureByDate($startDate, $endDate)
    {
        $temperatures = Temperature::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $temperatures;
    }

    public function getTemperatureByDeviceId($deviceId)
    {
        $temperatures = Temperature::where('device_id', $deviceId)
            ->get();

        return $temperatures;
    }

    public function getTemperatureByDateTime($date, $timeRange)
    {
        $time = explode('-', $timeRange);

        $startDateTime = Carbon::create($date . ' ' . $time['0'])->format("Y-m-d H:i:s");
        $endDateTime = Carbon::create($date . ' ' . $time['1'])->format("Y-m-d H:i:s");

        $temperatures = Temperature::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->get();

        return $temperatures;
    }
}
