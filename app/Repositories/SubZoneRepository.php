<?php

namespace App\Repositories;

use App\Interfaces\SubZoneRepositoryInterface;
use App\Models\SubZone;
use Illuminate\Http\Request;

class SubZoneRepository implements SubZoneRepositoryInterface
{

    public function getAllSubZones()
    {
        return SubZone::all();
    }

    public function getSubZone($subZone)
    {
        return $subZone;
    }

    public function deleteSubZone($subZone)
    {
        if ($subZone->deleteOrFail()) {
            return [
                'message' => 'SubZone deleted successfully',
            ];
        } else {
            return $subZone->deleteOrFail();
        }
    }

    public function createSubZone(Request $request)
    {
        $subZoneDetails = $request->only([
            'name',
            'zone_id',
            'detail',
        ]);

        return [
            'SubZone' => SubZone::create($subZoneDetails),
            'message' => 'SubZone created successfully',
        ];
    }

    public function updateSubZone($subZone, Request $request)
    {
        $newDetails = $request->only([
            'name',
            'zone_id',
            'detail',
        ]);

        if ($subZone->update($newDetails)) {
            return [
                'SubZone' => $subZone,
                'message' => 'SubZone updated successfully',
            ];
        } else {
            return $subZone->update($newDetails);
        }
    }
}
