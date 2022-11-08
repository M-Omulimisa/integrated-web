<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Session\TokenMismatchException;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson() || 
            $exception instanceof MethodNotAllowedHttpException && $request->expectsJson() || 
            $exception instanceof MethodNotAllowedHttpException && $request->bearerToken()
            ) {
            return \Response::json([
                'data' => null,
                'message' => 'Method is not allowed for the requested route',
                'status' => 405
            ], 405);
        }
        elseif ($exception instanceof ModelNotFoundException && $request->wantsJson() || 
                $exception instanceof ModelNotFoundException && $request->expectsJson() || 
                $exception instanceof ModelNotFoundException && $request->bearerToken()
                ) {
            return \Response::json([
                'data' => null,
                'message' => 'Model resource not found',
                'status' => 404
            ], 404);
        }
        elseif ($exception instanceof NotFoundHttpException && $request->wantsJson() || 
                $exception instanceof NotFoundHttpException && $request->expectsJson() || 
                $exception instanceof NotFoundHttpException && $request->bearerToken()
                ) {
            return \Response::json([
                'data' => null,
                'message' => 'Resource not found',
                'status' => 404
            ], 404);
        }
        elseif ($exception->getPrevious() instanceof TokenMismatchException && $request->wantsJson() || 
                $exception->getPrevious() instanceof TokenMismatchException && $request->expectsJson() || 
                $exception->getPrevious() instanceof TokenMismatchException && $request->bearerToken()
                ) {
            return \Response::json([
                'data' => null,
                'message' => 'Unauthenticated',
                'status' => 401
            ], 401);
        }
        elseif ($exception instanceof TokenMismatchException) {
            return redirect()->route('login');
        }

        return parent::render($request, $exception);
    }    
}
