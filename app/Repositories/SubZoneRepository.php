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
                'message' => 'زیر ناحیه با موفقیت حذف شد',
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
            'message' => 'زیر ناحیه با موفقیت ساخته شد',
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
                'message' => 'زیر ناحیه با موفقیت به روز رسانی شد',
            ];
        } else {
            return $subZone->update($newDetails);
        }
    }
}
