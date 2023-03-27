<?php

namespace App\Services;


use App\Models\Power;

class StorePowerService
{
    public function calculateAndStorePower($current, $voltage, $deviceId)
    {
        $power = $current['value'] * $voltage['value'];

        return Power::create(
            [
                'device_id' => $deviceId,
                'value' => $power,
            ]
        );
    }
}
