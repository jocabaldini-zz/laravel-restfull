<?php

namespace App\Exceptions;

use App\Constants\HttpStatusCodeConstants;
use App\Exceptions\AppException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AppException::class,  
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            Log::emergency('============ EXCEÇÃO NÃO PREVISTA ===============');
            Log::info('Dados da requisição: ');
            Log::info(json_encode(request()->all()));
            Log::info('URL: ' . request()->path());
            Log::info('Método: ' . request()->method());
            Log::debug(json_encode($exception));
            Log::debug(get_class($exception));/*
            Log::info('Status code: ' . $exception()->code());
            Log::info('Mensagem: ' . $exception->getMessage());
            Log::info('Stacktrace: ');
            Log::info($exception->getTraceAsString());*/
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $content = ['message' => __('exception.defaults.error')];
        $responseCode = HttpStatusCodeConstants::INTERNAL_SERVER_ERROR;
        
        if ($this->shouldntReport($exception)) {
            if ($exception instanceof AppException) {
                $responseCode = $exception->getCode();
                $message = $exception->getMessage();
            } elseif (
                $exception instanceof AuthenticationException ||
                $exception instanceof AuthorizationException ||
                $exception instanceof TokenMismatchException
            ) {
                $responseCode = HttpStatusCodeConstants::UNAUTHORIZED;
                $message = __('exception.defaults.unauthorized');
            } elseif ($exception instanceof HttpResponseException) {
                $responseCode = HttpStatusCodeConstants::FORBIDDEN;
                $message = __('exception.defaults.forbidden');
            } elseif (
                $exception instanceof ModelNotFoundException ||
                $exception instanceof HttpException                
            ) {
                $responseCode = HttpStatusCodeConstants::NOT_FOUND;
                $message = __('exception.defaults.not_found');;
            } elseif ($exception instanceof ValidationException) {
                $responseCode = HttpStatusCodeConstants::UNPROCESSABLE_ENTITY;
                $message = $exception->validator->getMessageBag();
            }
            $content = ['message' => $message];
        }
        
        return Response::make($content, $responseCode);
    }
}
