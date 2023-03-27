<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface HumidityRepositoryInterface
{
    public function getAllHumidities();
    public function createHumidity(Request $request);
    public function getHumidityByDate($startDate, $endDate);
    public function getHumidityByDeviceId($deviceId);
    public function getHumidityByDateTime($date, $timeRange);
    public function getAverageByDate($startDate, $endDate);
}
