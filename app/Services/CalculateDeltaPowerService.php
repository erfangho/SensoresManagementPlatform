<?php

namespace App\Services;


use App\Models\Order;
use App\Models\Power;

class CalculateDeltaPowerService
{
    public function calculateAndStoreDeltaPower($lastOrderId, $deviceId)
    {

        $pOne = 0;
        $pTwo = 0;
        $lastThreePowers = Power::query()->where('device_id', $deviceId)
            ->latest()
            ->limit(3)
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
                ->limit(3)
                ->pluck('value');


            foreach ($threePowersOfOneBeforeLastOrder as $power) {
                $pTwo += $power;
            }

            if (count($threePowersOfOneBeforeLastOrder) != 0) {
                $pTwo = $pTwo / count($threePowersOfOneBeforeLastOrder);
            }
        }

        $deltaPower = $pOne - $pTwo;
    }
}
