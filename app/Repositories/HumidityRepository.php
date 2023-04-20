<?php

namespace App\Repositories;

use App\Interfaces\HumidityRepositoryInterface;
use App\Models\Device;
use App\Models\Humidity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HumidityRepository implements HumidityRepositoryInterface
{
    public function getAllHumidities()
    {
        $humidities = Humidity::orderBy('created_at', 'desc')->take(100)->get();

        foreach ($humidities as $humiditie) {
            $device = Device::find($humiditie['device_id']);

            $humiditie['device_name'] = $device['name'];
        }

        return $humidities;
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

//        Redis::set('Humidities:' . $keyNameRedisDate . ':' . $keyNameRedisHour, json_encode([
//            'device_id' => $Humidity['device_id'],
//            'value' => $Humidity['value'],
//        ]));

        // TODO this response is only for stage, for production layer we should return device orders
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

    public function getAverageByDate($startDate, $endDate)
    {
        $averages = DB::table('humidities')
            ->select(DB::raw('AVG(value) as average, DATE(created_at) as date'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('date')
            ->get();

        return $averages;
    }
}
