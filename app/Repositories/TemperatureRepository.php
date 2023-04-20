<?php

namespace App\Repositories;

use App\Interfaces\TemperatureRepositoryInterface;
use App\Models\Device;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TemperatureRepository implements TemperatureRepositoryInterface
{
    public function getAllTemperatures($request)
    {
        $temperatures = Temperature::orderBy('created_at', 'desc')->take(100)->get();

        foreach ($temperatures as $temperature) {
            $device = Device::find($temperature['device_id']);

            $temperature['device_name'] = $device['name'];
        }

        return $temperatures;
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

//        Redis::set('Tepmerature:' . $keyNameRedisDate . ':' . $keyNameRedisHour, json_encode([
//            'device_id' => $temperature['device_id'],
//            'value' => $temperature['value'],
//        ]));

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

    public function getAverageByDate($request, $startDate, $endDate)
    {
        try {
            $data = Temperature::selectRaw('AVG(temperatures.value) as average, DATE(temperatures.created_at) as date')
                ->join('devices', 'temperatures.device_id', '=', 'devices.id')
                ->join('sub_zones', 'devices.sub_zone_id', '=', 'sub_zones.id')
                ->join('zones', 'sub_zones.zone_id', '=', 'zones.id')
                ->when($request->has('zone_id'), function ($query) use ($request) {
                    $query->where('zones.id', '=', $request['zone_id']);
                })
                ->when($request->has('sub_zone_id'), function ($query) use ($request) {
                    $query->where('sub_zones.id', '=', $request['sub_zone_id']);
                })
                ->when($request->has('device_id'), function ($query) use ($request) {
                    $query->where('temperatures.device_id', '=', $request['device_id']);
                })
                ->whereBetween('temperatures.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date');
        } catch (\Exception $exception) {

        }

        if (auth()->user()['role_id'] == config('constants.roles.admin')) {
            return $data->get();
        } else {
            return $data->join('users', 'users.id', '=', 'devices.user_id')
                ->where('users.id', '=', auth()->user()['id'])->get();
        }
    }
}
