<?php

namespace App\Http\Controllers\Amounts;

use App\Http\Controllers\Controller;
use App\Services\CalculateDeltaPowerService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DeltaPowerController extends Controller
{
    public function getDeltaPower(Request $request, $start, $end)
    {
        $calculateDeltaPowerService = new CalculateDeltaPowerService();

        $deltaPower = $calculateDeltaPowerService->getDeltaPowerNumber($request, $start, $end);

        return response()->json([
            'deltaPower' => -$deltaPower,
        ], ResponseAlias::HTTP_OK);
    }
}
