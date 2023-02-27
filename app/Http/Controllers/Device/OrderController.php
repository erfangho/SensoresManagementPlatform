<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceRequests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::all(), ResponseAlias::HTTP_OK);
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

//        dd($orderDetails);
        $order = Order::create($orderDetails);

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
