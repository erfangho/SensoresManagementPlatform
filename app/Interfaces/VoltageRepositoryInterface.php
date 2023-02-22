<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface VoltageRepositoryInterface
{
    public function getAllVoltages();
    public function createVoltage(Request $request);
    public function getVoltageByDate($startDate, $endDate);
    public function getVoltageByDeviceId($deviceId);
    public function getVoltageByDateTime($date, $timeRange);
}
