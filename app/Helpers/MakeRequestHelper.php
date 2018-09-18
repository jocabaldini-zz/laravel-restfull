<?php

namespace App\Helpers;

use App\Constants\HttpStatusCodeConstants;
use App\Exceptions\AppException;
use App\Services\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

abstract class MakeRequestHelper
{
    public static function sendRequest(Service $service, string $method, array $params)
    {
        return self::makeRequest([
            'service' => $service,
            'method' => $method,
            'params' => $params
        ]);
    }

	private static function makeRequest(array $request) : JsonResponse
	{
        $timeStart = microtime(true);
        $content = null;
        $code = null;

        DB::transaction(function () use ($request, &$content, &$responseCode, $timeStart) {
            $service = $request['service'];
            $serviceName = get_class($service);
            $method  = $request['method'];
            $params  = $request['params'];

            $response = $service->$method($params);

            $content = $response['content'];
            $responseCode = $response['status'] ?? HttpStatusCodeConstants::OK;
            
            Log::info("[Servico: {$serviceName}| Metodo: {$method}] Time: " 
                . round((microtime(true) - $timeStart) * 1000) . " ms");
        });

        return Response::json($content, $responseCode);
    }
}