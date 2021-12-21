<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponser; 
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ModelNotFoundException $e, $request) {
            return $this->errorResponse('Entry for ' . str_replace('App\\', '', $e->getModel()) . ' not found', 404);
        });
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            return $this->errorResponse('Too Many Requests,Please Slow Down', 429);
        });
        $this->renderable(function (PostTooLargeException $e, $request) {
            return $this->errorResponse('Size of attached file should be less', 400);
        });
        $this->renderable(function (ValidationException $e, $request) {
            return $this->errorResponse($e->getMessage(), 422, $e->errors());
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            return $this->errorResponse($e->getMessage(), 403);
        });

        // $this->renderable(function (\Error $e, $request) {
        //     return $this->errorResponse('There was some internal error', 500);
        // });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
