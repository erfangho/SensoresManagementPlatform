<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $device = Device::findOrFail($request['device_id']);

        if ($device['api_key'] != $request['api_key']) {
            return response()->json([
                'message' => 'API key is not correct',
            ],ResponseAlias::HTTP_FORBIDDEN);
        } else {
            return $next($request);
        }
    }
}
