<?php

namespace App\Repositories;

use App\Interfaces\ZoneRepositoryInterface;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneRepository implements ZoneRepositoryInterface
{

    public function getAllZones()
    {
        return Zone::all();
    }

    public function getZone($zone)
    {
        return $zone;
    }

    public function deleteZone($zone)
    {
        if ($zone->deleteOrFail()) {
            return [
                'message' => 'ناحیه با موفقیت حذف شد',
            ];
        } else {
            return $zone->deleteOrFail();
        }
    }

    public function createZone(Request $request)
    {
        $zoneDetails = $request->only([
            'name',
            'address',
            'detail',
        ]);

        return [
            'Zone' => Zone::create($zoneDetails),
            'message' => 'ناحیه با موفقیت ساخته شد',
        ];
    }

    public function updateZone($zone, Request $request)
    {
        $newDetails = $request->only([
            'name',
            'address',
            'detail',
        ]);

        if ($zone->update($newDetails)) {
            return [
                'Zone' => $zone,
                'message' => 'ناحیه با موفقیت به روز رسانی شد',
            ];
        } else {
            return $zone->update($newDetails);
        }
    }
}
