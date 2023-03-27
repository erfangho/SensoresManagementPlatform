<?php

namespace App\Repositories;

use App\Interfaces\CurrentRepositoryInterface;
use App\Interfaces\VoltageRepositoryInterface;
use App\Models\Current;
use App\Models\Device;
use App\Models\Voltage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class VoltageRepository implements VoltageRepositoryInterface
{
    public function getAllVoltages()
    {
        $voltages = Voltage::all();

        foreach ($voltages as $voltage) {
            $device = Device::find($voltage['device_id']);

            $voltage['device_name'] = $device['name'];
        }

        return $voltages;
    }

    public function createVoltage(Request $request)
    {
        $voltageDetails = $request->only([
            'value',
            'device_id',
            'detail',
        ]);

        $voltage = Voltage::create($voltageDetails);

        $keyNameRedisDate = Carbon::createFromFormat('Y-m-d H:i:s', $voltage['created_at'])
            ->format('Y-m-d');

        $keyNameRedisHour = Carbon::createFromFormat('Y-m-d H:i:s', $voltage['created_at'])
            ->format('H:i');

//        Redis::set('Voltages:' . $keyNameRedisDate . ':' . $keyNameRedisHour, json_encode([
//            'device_id' => $voltage['device_id'],
//            'value' => $voltage['value'],
//        ]));

        // TODO this response is only for stage, for production layer we should return device orders
        return [
            'Voltage' => $voltage,
            'message' => 'Voltage created successfully',
        ];
    }

    public function getVoltageByDate($startDate, $endDate)
    {
        $voltages = Voltage::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $voltages;
    }

    public function getVoltageByDeviceId($deviceId)
    {
        $voltages = Voltage::where('device_id', $deviceId)
            ->get();

        return $voltages;
    }

    public function getVoltageByDateTime($date, $timeRange)
    {
        $time = explode('-', $timeRange);

        $startDateTime = Carbon::create($date . ' ' . $time['0'])->format("Y-m-d H:i:s");
        $endDateTime = Carbon::create($date . ' ' . $time['1'])->format("Y-m-d H:i:s");

        $voltages = Voltage::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->get();

        return $voltages;
    }
}
