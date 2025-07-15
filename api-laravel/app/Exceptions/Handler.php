<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        
        $this->renderable(function (Exception $exception, $request) {

            if (method_exists($exception, 'getStatusCode')) {
                $statusCode = $exception->getStatusCode();
            } else {
                $statusCode = 500;
            }
        
            $response = [];
        
            switch ($statusCode) {
                case 401:
                    $response['message'] = 'Unauthorized';
                    break;
                case 403:
                    $response['message'] = 'Forbidden';
                    break;
                case 404:
                    $response['message'] = 'Not Found';
                    break;
                case 405:
                    $response['message'] = 'Method Not Allowed';
                    break;
                case 422:
                    $response['message'] = $exception->original['message'];
                    $response['errors'] = $exception->original['errors'];
                    break;
                default:
                    $response['message'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                    break;
            }
        
            
        
            $response['status'] = $statusCode;

            if ($request->is('api/*')) {
                return response()->json($response, $statusCode );
            }
        });
    }

   
}