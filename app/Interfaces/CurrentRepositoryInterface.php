<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CurrentRepositoryInterface
{
    public function getAllCurrents();
    public function createCurrent(Request $request);
    public function getCurrentByDate($startDate, $endDate);
    public function getCurrentByDeviceId($deviceId);
    public function getCurrentByDateTime($date, $timeRange);
    public function getAverageByDate($request, $startDate, $endDate);
}
