<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Power;
use Illuminate\Http\Request;

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
}
