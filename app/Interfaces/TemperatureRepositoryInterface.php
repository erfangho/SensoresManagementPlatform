<?php

namespace App\Interfaces;

use App\Models\Temperature;
use Illuminate\Http\Request;

interface TemperatureRepositoryInterface
{
    public function getAllTemperatures();
    public function getTemperatureByDate($startDate, $endDate);
    public function getTemperatureByDeviceId($deviceId);
    public function getTemperatureByDateTime($date, $startTime, $endTime);
    public function createTemperature(Request $request);
}
