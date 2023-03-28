<?php

namespace App\Repositories;

use App\Interfaces\CurrentRepositoryInterface;
use App\Models\Current;
use App\Models\Device;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CurrentRepository implements CurrentRepositoryInterface
{
    public function getAllCurrents()
    {
        $currents = Current::all();

        foreach ($currents as $current) {
            $device = Device::find($current['device_id']);

            $current['device_name'] = $device['name'];
        }

        return $currents;
    }

    public function createCurrent(Request $request)
    {
        $currentDetails = $request->only([
            'value',
            'device_id',
            'detail',
        ]);

        $current = Current::create($currentDetails);

        $keyNameRedisDate = Carbon::createFromFormat('Y-m-d H:i:s', $current['created_at'])
            ->format('Y-m-d');

        $keyNameRedisHour = Carbon::createFromFormat('Y-m-d H:i:s', $current['created_at'])
            ->format('H:i');

//        Redis::set('Currents:' . $keyNameRedisDate . ':' . $keyNameRedisHour, json_encode([
//            'device_id' => $current['device_id'],
//            'value' => $current['value'],
//        ]));

        // TODO this response is only for stage, for production layer we should return device orders
        return [
            'Current' => $current,
            'message' => 'Current created successfully',
        ];
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

    public function getAverageByDate($request, $startDate, $endDate)
    {
        try {
            $data = Current::selectRaw('AVG(currents.value) as average, DATE(currents.created_at) as date')
                ->join('devices', 'currents.device_id', '=', 'devices.id')
                ->join('sub_zones', 'devices.sub_zone_id', '=', 'sub_zones.id')
                ->join('zones', 'sub_zones.zone_id', '=', 'zones.id')
                ->when($request->has('zone_id'), function ($query) use ($request) {
                    $query->where('zones.id', '=', $request['zone_id']);
                })
                ->when($request->has('sub_zone_id'), function ($query) use ($request) {
                    $query->where('sub_zones.id', '=', $request['sub_zone_id']);
                })
                ->when($request->has('device_id'), function ($query) use ($request) {
                    $query->where('currents.device_id', '=', $request['device_id']);
                })
                ->whereBetween('currents.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderByDesc('date')
                ->get();

            return $data;
        } catch (\Exception $exception) {

        }
    }
}
