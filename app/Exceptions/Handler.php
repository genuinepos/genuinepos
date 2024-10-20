<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        $this->renderable(function (Throwable $e, $request) {
            // Log the exception for debugging purposes
            if ($e instanceof \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException) {
      
                // Return the custom 404 error page
                return response()->view('errors.404', [], 404);
            }
        });

        // $this->reportable(function (Throwable $e) {

        //     $exceptionClass = get_class($e); // Get the class of the exception
        //     // dd($exceptionClass);
        //     Log::info('Class Name: ' . $exceptionClass);
        //     Log::info('Msg: ' . $e);

        //     if ($exceptionClass == "Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException") {
        //         // Return a 404 Not Found response
        //         // dd($exceptionClass);
        //         Log::info('Class Name: FFFFFFFF ' . $exceptionClass);
        //         return redirect()->back();
        //         // return response()->view('errors.404', [], 404);
        //     }
        // });
    }
}
