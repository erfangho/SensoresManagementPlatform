<?php

namespace App\Repositories;

use App\Interfaces\HumidityRepositoryInterface;
use App\Models\Device;
use App\Models\Humidity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HumidityRepository implements HumidityRepositoryInterface
{
    public function getAllHumidities()
    {
        return Humidity::all();
    }

    public function createHumidity(Request $request)
    {
        $humidityDetails = $request->only([
            'value',
            'device_id',
            'detail',
        ]);

        $Humidity = Humidity::create($humidityDetails);

        $keyNameRedisDate = Carbon::createFromFormat('Y-m-d H:i:s', $Humidity['created_at'])
                            ->format('Y-m-d');

        $keyNameRedisHour = Carbon::createFromFormat('Y-m-d H:i:s', $Humidity['created_at'])
                            ->format('H:i');

        Redis::set('Humidities:' . $keyNameRedisDate . ':' . $keyNameRedisHour, json_encode([
            'device_id' => $Humidity['device_id'],
            'value' => $Humidity['value'],
        ]));

        return [
            'Humidity' => $Humidity,
            'message' => 'Humidity created successfully',
        ];
    }

    public function getHumidityByDate($startDate, $endDate)
    {
        $Humidities = Humidity::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $Humidities;
    }

    public function getHumidityByDeviceId($deviceId)
    {
        $Humidities = Humidity::where('device_id', $deviceId)
            ->get();

        return $Humidities;
    }

    public function getHumidityByDateTime($date, $timeRange)
    {
        $time = explode('-', $timeRange);

        $startDateTime = Carbon::create($date . ' ' . $time['0'])->format("Y-m-d H:i:s");
        $endDateTime = Carbon::create($date . ' ' . $time['1'])->format("Y-m-d H:i:s");

        $Humidities = Humidity::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->get();

        return $Humidities;
    }
}
