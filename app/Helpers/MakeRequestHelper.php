<?php

namespace App\Helpers;

use App\Exceptions\AppException;
use App\Http\Helpers\HttpStatusCode;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

abstract class MakeRequestHelper
{
    public static function sendRequest(string $service, string $method, array $params)
    {
        return self::makeRequest([
            'service' => $service,
            'method' => $method,
            'params' => $params
        ]);
    }

	private static function makeRequest(array $request)
	{
        $timeStart = microtime(true);
        $content = null;
        $responseCode = null;

        DB::transaction(function () use ($request, &$content, &$responseCode, $timeStart) {
            $service = $request['service'];
            $method  = $request['method'];
            $params  = $request['params'];

            $response = $service->$method($params);

            $content = $response['response'];
            $responseCode = $response['status'] ?? HttpStatusCode::OK;
            
            Log::debug("[Servico: {$serviceName}| Metodo: {$method}] Time: " 
                . round((microtime(true) - $timeStart) * 1000) . " ms");
        });

        return Response::make($content, $responseCode);
    }
}