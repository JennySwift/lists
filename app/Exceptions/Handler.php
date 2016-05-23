<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Stripe\Error\ApiConnection;
use Stripe\Error\Authentication;
use Stripe\Error\Base;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Stripe\Error\RateLimit;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $model = (new \ReflectionClass($e->getModel()))->getShortName();

            return response([
                'error' => "{$model} not found.",
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof NotLoggedInException) {
            return response([
                'error' => 'You are not logged in',
                'status' => Response::HTTP_UNAUTHORIZED
            ], Response::HTTP_UNAUTHORIZED);
        }

        //Stripe error handling
        if ($e instanceof Card) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof RateLimit) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof InvalidRequest) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof Authentication) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof ApiConnection) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof Base) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof Exception) {
            return response([
                'error' => $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return parent::render($request, $e);
    }
}
