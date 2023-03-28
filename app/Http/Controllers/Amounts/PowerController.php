<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Power;
use App\Models\Temperature;
use App\Models\Voltage;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class PowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $powers = Power::all();

        foreach ($powers as $power) {
            $device = Device::find($power['device_id']);

            $power['device_name'] = $device['name'];
        }

        return $powers;
    }

    public function getAverageByDate(Request $request, $start, $end)
    {
        try {
            $data = Power::selectRaw('AVG(powers.value) as average, DATE(powers.created_at) as date')
                ->join('devices', 'powers.device_id', '=', 'devices.id')
                ->join('sub_zones', 'devices.sub_zone_id', '=', 'sub_zones.id')
                ->join('zones', 'sub_zones.zone_id', '=', 'zones.id')
                ->when($request->has('zone_id'), function ($query) use ($request) {
                    $query->where('zones.id', '=', $request['zone_id']);
                })
                ->when($request->has('sub_zone_id'), function ($query) use ($request) {
                    $query->where('sub_zones.id', '=', $request['sub_zone_id']);
                })
                ->when($request->has('device_id'), function ($query) use ($request) {
                    $query->where('powers.device_id', '=', $request['device_id']);
                })
                ->whereBetween('powers.created_at', [$start, $end])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderByDesc('date')
                ->get();

            return response()->json([
                'Powers' => $data,
            ], ResponseAlias::HTTP_OK);
        } catch (\Exception $exception) {

        }
    }

    public function exportPowersAsCsv()
    {
        $powers = Power::all();

        $exportService = new ExportService();

        return $exportService->exportCsv(
            $powers,
            'powers.csv',
            ['value', 'device_id', 'created_at']);
    }
}
