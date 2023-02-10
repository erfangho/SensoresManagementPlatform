<?php

namespace App\Interfaces;

use App\Models\SubZone;
use Illuminate\Http\Request;

interface SubZoneRepositoryInterface
{
    public function getAllSubZones();
    public function getSubZone(SubZone $subZone);
    public function deleteSubZone(SubZone $subZone);
    public function createSubZone(Request $request);
    public function updateSubZone(SubZone $subZone, Request $request);
}
