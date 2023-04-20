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
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $itemsPerPage = isset($request['items_count']) && (int)$request['items_count']
                        ? (int)$request['items_count'] : 10;

        $orders = Order::orderBy('created_at', 'desc')->paginate($itemsPerPage);
        $count = $orders->total();

        foreach ($orders as $order) {
            $user = User::find($order['user_id']);
            $device = Device::find($order['device_id']);

            $order['user_name'] = $user['name'];
            $order['deivice_name'] = $device['name'];
        }


        return response()->json([
            'Orders' => $orders->items(),
            'count' => $count,
        ], ResponseAlias::HTTP_OK);
    }

    public function store(StoreOrderRequest $request)
    {
        $orderDetails = $request->only([
            'user_id',
            'device_id',
            'zone_id',
            'temperature',
            'power',
        ]);

        $orderDetails['status'] = config('constants.DEVICE_POWER_STATUS.standBy');

        if ($request->has('zone_id')) {
            $devices = DB::table('devices')
                ->join('sub_zones', 'devices.sub_zone_id', '=', 'sub_zones.id')
                ->join('zones', 'sub_zones.zone_id', '=', 'zones.id')
                ->where('zones.id', '=', $request['zone_id'])
                ->select('devices.*')
                ->get();

            foreach ($devices as $device) {
                
            }
        } else {
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
}
