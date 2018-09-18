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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    protected $appExceptions = [
        AppException::class,
    ];

    protected $unauthorizedExceptions = [
        AuthenticationException::class,
        AuthorizationException::class,
        TokenMismatchException::class,
    ];

    protected $forbiddenExceptions = [
        HttpResponseException::class,
    ];

    protected $notFoundExceptions = [
        ModelNotFoundException::class,
        HttpException::class,
        NotFoundHttpException::class,
    ];

    protected $validationExceptions = [
        ValidationException::class,
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
            Log::emergency(    
                '======================== EXCEÇÃO NÃO PREVISTA ========================' . PHP_EOL .
                'Dados da requisição: ' . PHP_EOL .
                json_encode(request()->all()) . PHP_EOL .
                'URL: ' . request()->path() . PHP_EOL .
                'Método: ' . request()->method() . PHP_EOL .
                'Mensagem: ' . $exception->getMessage() . PHP_EOL .
                'Stracktrace:' . PHP_EOL .
                $exception->getTraceAsString() . PHP_EOL .
                '======================================================================'
            );
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
        list($content, $code) = $this->handleException($exception);

        return Response::json($content, $code);
    }

    private function handleException(Exception $exception) : array
    {
        if ($this->isAppException($exception)) {
            $message = $exception->getMessage();
            $code = $exception->getCode();
        } elseif ($this->isUnauthorizedException($exception)) {
            $message = __('exception.unauthorized');
            $code = HttpStatusCodeConstants::UNAUTHORIZED;
        } elseif ($this->isForbiddenException($exception)) {
            $message = __('exception.forbidden');
            $code = HttpStatusCodeConstants::FORBIDDEN;
        } elseif ($this->isNotFoudException($exception)) {
            $message = __('exception.not_found');
            $code = HttpStatusCodeConstants::NOT_FOUND;
        } elseif ($this->isValidationException($exception)) {
            $message = $exception->validator->getMessageBag();
            $code = HttpStatusCodeConstants::UNPROCESSABLE_ENTITY;
        } else {
            $message = __('exception.defaults.error');
            $code = HttpStatusCodeConstants::INTERNAL_SERVER_ERROR;
        }

        $content = ['message' => $message];

        return [$content, $code];
    }

    private function isAppException(Exception $exception) : bool
    {
        return in_array(get_class($exception), $this->appExceptions);
    }

    private function isUnauthorizedException(Exception $exception) : bool
    {
        return in_array(get_class($exception), $this->unauthorizedExceptions);
    }

    private function isForbiddenException(Exception $exception) : bool
    {
        return in_array(get_class($exception), $this->forbiddenExceptions);
    }

    private function isNotFoudException(Exception $exception) : bool
    {
        return in_array(get_class($exception), $this->notFoundExceptions);
    }

    private function isValidationException(Exception $exception) : bool
    {
        return in_array(get_class($exception), $this->validationExceptions);
    }
}
