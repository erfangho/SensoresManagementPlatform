<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceRequests\StoreOrderRequest;
use App\Models\Device;
use App\Models\Order;
use App\Models\SubZone;
use App\Services\CalculateDeltaPowerService;
use App\Services\OrderReportService;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $user = User::find($order['user_id']);
            $device = Device::find($order['device_id']);

            $order['user_name'] = $user['name'];
            $order['deivice_name'] = $device['name'];
        }


        return response()->json($orders, ResponseAlias::HTTP_OK);
    }

    public function store(StoreOrderRequest $request)
    {
        $orderDetails = $request->only([
            'user_id',
            'device_id',
            'temperature',
            'power',
        ]);

        $orderDetails['status'] = config('constants.DEVICE_POWER_STATUS.standBy');

        $order = Order::create($orderDetails);

        $calculateDeltaPowerService = new CalculateDeltaPowerService();
        $calculateDeltaPowerService->calculateAndStoreDeltaPower($order['id'], $order['device_id']);

        return response()->json(
            [
                'message' => 'دستورات با موفقیت ثبت شد',
                'data' => [
                    'user_id' => $orderDetails['user_id'],
                    'device_id' => $orderDetails['device_id'],
                    'status' => $orderDetails['status'],
                ],
            ],
            ResponseAlias::HTTP_CREATED
        );
    }
}
