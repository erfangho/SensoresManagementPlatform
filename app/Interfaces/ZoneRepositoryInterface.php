<?php

namespace App\Interfaces;

use App\Models\Zone;
use Illuminate\Http\Request;

interface ZoneRepositoryInterface
{
    public function getAllZones();
    public function getZone(Zone $Zone);
    public function deleteZone(Zone $Zone);
    public function createZone(Request $request);
    public function updateZone(Zone $Zone, Request $request);
}
