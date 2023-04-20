<?php

namespace App\Interfaces;

use App\Models\Temperature;
use Illuminate\Http\Request;

interface TemperatureRepositoryInterface
{
    public function getAllTemperatures($request);
    public function getTemperatureByDate($startDate, $endDate);
    public function getTemperatureByDeviceId($deviceId);
    public function getTemperatureByDateTime($date, $timeRange);
    public function createTemperature(Request $request);
    public function getAverageByDate($request, $startDate, $endDate);
}
