<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function logError($request, $e)
    {
        // kalau requestnya adalah instance dari Request
        if ($request instanceof \Illuminate\Http\Request) {
            $request = $request->all();
        }

        // kalau development liatkan saja bugnya
        if (config('app.debug')) {
            dd($request, $e);
        }

        // kalau production catat saja log
        $logs = [
            'request' => $request,
            'error' => 'error', $e->getMessage() . ' - ' . $e->getLine() . ' - ' . $e->getFile(),
        ];

        Log::info(json_encode($logs));
    }
}
