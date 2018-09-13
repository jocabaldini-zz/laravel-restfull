<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Constants\HttpStatusCodeConstants;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
            $content = ['message' => $exception->getMessage()];
            
            if ($exception instanceof AppException) {
                $responseCode = $e->getCode();
            } elseif (
                $exception instanceof AuthenticationException ||
                $exception instanceof AuthorizationException ||
                $exception instanceof TokenMismatchException
            ) {
                $responseCode = HttpStatusCodeConstants::UNAUTHORIZED;
            } elseif ($exception instanceof HttpResponseException) {
                $responseCode = HttpStatusCodeConstants::FORBIDDEN;
            } elseif (
                $exception instanceof ModelNotFoundException ||
                $exception instanceof HttpException                
            ) {
                $responseCode = HttpStatusCodeConstants::NOT_FOUND;
            } elseif ($exception instanceof ValidationException) {
                $responseCode = HttpStatusCodeConstants::UNPROCESSABLE_ENTITY;
                $content['message'] = $exception->validator->getMessageBag();
            }
        }
        
        return Response::make($content, $responseCode);
    }
}
