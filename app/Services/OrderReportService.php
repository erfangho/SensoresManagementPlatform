<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Order;

class OrderReportService
{
    public function getStandByOrders($deviceId)
    {
        $order = Order::where('device_id', $deviceId)
            ->where('status', 'stand by')->get()->last();

        $device = Device::findOrFail($deviceId);

        if (isset($order['status'])) {
            $order['status'] = config('constants.DEVICE_POWER_STATUS.sent');
            $order->update();

            if ($order['temperature'] != null and $order['power'] == null) {
                return response()->json([
                    'device_id' => $device['name'],
                    'temperature' => $order['temperature'],
                ]);
            } elseif ($order['power'] != null and $order['temperature'] == null) {
                return response()->json([
                    'device_id' => $device['name'],
                    'power' => $order['power'],
                ]);
            } elseif ($order['power'] != null and $order['temperature'] != null) {
                return response()->json([
                    'device_id' => $device['name'],
                    'power' => $order['power'],
                    'temperature' => $order['temperature'],
                ]);
            }
        }
    }
}
