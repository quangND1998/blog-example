<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        if (env('APP_ENV') === 'production' && app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
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
        if (env('APP_ENV') === 'production') {
            switch ($exception) {
                case ($exception instanceof PostTooLargeException):
                    return $this->setUploadResponse();
                    break;
                case ($exception instanceof NotFoundHttpException):
                    return $this->setResponse(__('messages.exception.api_not_found'));
                case ($exception instanceof MethodNotAllowedHttpException):
                    return $this->setResponse(__('messages.exception.method_not_allow'));
                case ($exception instanceof UnauthorizedHttpException):
                    return $this->setResponse(__('messages.exception.unauthenticate'));
                    break;
                case ($exception instanceof AuthorizationException):
                    return $this->setResponse(__('messages.exception.authorization'));
                    break;
                case ($exception instanceof ModelNotFoundException):
                    return $this->setResponse(__('messages.exception.model_not_found'));
                    break;
                case ($exception instanceof ValidationException):
                    return $this->setFormResponse(__('messages.exception.invalid_data'), $exception);
                    break;
                case ($exception instanceof HttpException):
                    return $this->setResponse(__('messages.exception.internal_error'));
                    break;
                case ($exception instanceof QueryException):
                    return $this->setResponse(__('messages.exception.internal_error'));
                    break;
                default:
                    return $this->setResponse([
                        'msg' => $exception->getMessage(),
                        'status' => $exception->getCode() ?: 500,
                    ]);
                    break;
            }
        } else {
            return parent::render($request, $exception);
        }
    }

    /**
     * Set custom response struct
     *
     * @param array $info
     *
     * @return json
     */
    private function setResponse($info)
    {
        return response()->json(['meta' => ['message' => $info['msg']]], $info['status']);
    }

    /**
     * Set custom response struct for form error
     *
     * @param array $info
     * @param Error $error
     *
     * @return json
     */
    private function setFormResponse($info, $error)
    {
        return response()->json([
            'message' => __('Dữ liệu không hợp lệ'),
            'other' => $error->errors(),
        ], $info['status']);
    }

    /**
     * Set custom response struct for upload image error
     *
     * @return json
     */
    private function setUploadResponse()
    {
        return response()->json([
            'message' => __('Dữ liệu không hợp lệ'),
            'other' => [
                'img' => [__('Ảnh tối đa 2Mb')],
            ],
        ], 422);
    }
}
