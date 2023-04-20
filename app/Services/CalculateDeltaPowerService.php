<?php

namespace App\Services;


use App\Models\DeltaPower;
use App\Models\Order;
use App\Models\Power;
use Illuminate\Support\Facades\DB;

class CalculateDeltaPowerService
{
    public function calculateAndStoreDeltaPower($lastOrderId, $deviceId)
    {

        $pOne = 0;
        $pTwo = 0;
        $oneBeforeLastOrderId = null;

        $lastThreePowers = Power::query()->where('device_id', $deviceId)
            ->latest()
            ->limit(5)
            ->pluck('value');

        foreach ($lastThreePowers as $power) {
            $pOne += $power;
        }

        if (count($lastThreePowers) != 0) {
            $pOne = $pOne / count($lastThreePowers);
        }

        $oneBeforeLastOrder = Order::query()->where('device_id', $deviceId)
            ->skip(1)
            ->take(1)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($oneBeforeLastOrder) {
            $threePowersOfOneBeforeLastOrder = Power::query()->where('device_id', $deviceId)
                ->where('created_at', '<', $oneBeforeLastOrder['created_at'])
                ->latest()
                ->limit(5)
                ->pluck('value');


            foreach ($threePowersOfOneBeforeLastOrder as $power) {
                $pTwo += $power;
            }

            if (count($threePowersOfOneBeforeLastOrder) != 0) {
                $pTwo = $pTwo / count($threePowersOfOneBeforeLastOrder);
            }

            $oneBeforeLastOrderId = $oneBeforeLastOrder['id'];
        } else {
            $oneBeforeLastOrderId = $lastOrderId;
        }

        $deltaPower = $pOne - $pTwo;
        if ($deltaPower != 0) {
            return DeltaPower::create([
                'device_id' => $deviceId,
                'value' => $deltaPower,
                'first_order_id' => $oneBeforeLastOrderId,
                'second_order_id' => $lastOrderId,
            ]);
        }
    }

    public function getDeltaPowerNumber($request, $startDate, $endDate)
    {
        $finalDeltaPower = 0;
        $data = DeltaPower::select('delta_powers.value', 'delta_powers.created_at')
            ->join('devices', 'delta_powers.device_id', '=', 'devices.id')
            ->join('sub_zones', 'devices.sub_zone_id', '=', 'sub_zones.id')
            ->join('zones', 'sub_zones.zone_id', '=', 'zones.id')
            ->when($request->has('zone_id'), function ($query) use ($request) {
                $query->where('zones.id', '=', $request['zone_id']);
            })
            ->when($request->has('sub_zone_id'), function ($query) use ($request) {
                $query->where('sub_zones.id', '=', $request['sub_zone_id']);
            })
            ->when($request->has('device_id'), function ($query) use ($request) {
                $query->where('delta_powers.device_id', '=', $request['device_id']);
            })
            ->where('value', '<', 0)
            ->whereBetween('delta_powers.created_at', [$startDate, $endDate])
            ->orderByDesc('delta_powers.created_at')
            ->pluck('value');

            foreach ($data as $datum) {
                $finalDeltaPower += $datum;
            }

            return $finalDeltaPower;
    }
}
