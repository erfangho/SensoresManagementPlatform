<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Power;
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

    public function getAverageByDate($start, $end)
    {
        $averages = DB::table('powers')
            ->select(DB::raw('AVG(value) as average, DATE(created_at) as date'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'Powers' => $averages,
        ], ResponseAlias::HTTP_OK);
    }
}
