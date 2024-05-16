<?php

// namespace App\Exceptions;

// use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
// use Illuminate\Http\Exceptions\HttpResponseException;
// use Symfony\Component\HttpKernel\Exception\HttpException;
// use Throwable;
// use Illuminate\Support\Facades\Log;
// use Exception;
// use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
// use Symfony\Component\ErrorHandler\Exception\FlattenException;
// use Illuminate\Http\Response;
// use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Validation\ValidationException;
// use Illuminate\Auth\Access\AuthorizationException;
// use Illuminate\Database\QueryException;
// use Illuminate\Contracts\Support\Responsable;

// class Handler extends ExceptionHandler
// {
//     /**
//      * The list of the inputs that are never flashed to the session on validation exceptions.
//      *
//      * @var array<int, string>
//      */
//     protected $dontFlash = [
//         'current_password',
//         'password',
//         'password_confirmation',
//     ];

//     /**
//      * Register the exception handling callbacks for the application.
//      */
//     public function register(): void
//     {
//         $this->reportable(function (Throwable $e) {
//             //
//         });
//     }

//     public function render($request, Throwable $exception)
//     {
//         // Log the exception using the Log facade
//         Log::error('Exception: ' . $exception->getMessage(), [
//             'file' => $exception->getFile(),
//             'line' => $exception->getLine(),
//             'code' => $exception->getCode(),
//             // Add any additional context you want to log
//         ]);

//         // Customize error messages based on the exception type
//         if ($exception instanceof HttpException) {
//             return response()->view('error', [
//                 'error' => [
//                     'message' => 'HTTP Exception',
//                     'description' => 'An HTTP error occurred.',
//                     'redirectUrl' => '/home',
//                     'redirectName' => 'Go Home',
//                 ]
//             ], $exception->getStatusCode());
//         } elseif ($exception instanceof ModelNotFoundException) {
//             return response()->view('error', [
//                 'error' => [
//                     'message' => 'Model Not Found',
//                     'description' => 'The requested model was not found.',
//                     'redirectUrl' => '/home',
//                     'redirectName' => 'Go Home',
//                 ]
//             ], 404);
//         } elseif ($exception instanceof ValidationException) {
//             $errors = $exception->validator->getMessageBag();
//             return response()->view('error', [
//                 'error' => [
//                     'message' => 'Validation Error',
//                     'description' => 'The input data validation failed.',
//                     'redirectUrl' => '/home',
//                     'redirectName' => 'Go Home',
//                     'validationErrors' => $errors,
//                 ]
//             ], 422);
//         } elseif ($exception instanceof AuthorizationException) {
//             return response()->view('error', [
//                 'error' => [
//                     'message' => 'Authorization Error',
//                     'description' => 'You are not authorized to perform this action.',
//                     'redirectUrl' => '/home',
//                     'redirectName' => 'Go Home',
//                 ]
//             ], 403);
//         } elseif ($exception instanceof QueryException) {
//             return response()->view('error', [
//                 'error' => [
//                     'message' => 'Query Exception',
//                     'description' => 'A database query error occurred.',
//                     'redirectUrl' => '/home',
//                     'redirectName' => 'Go Home',
//                 ]
//             ], 500);
//         }

//         // Let the parent class handle unhandled exceptions
//         return parent::render($request, $exception);
//     }
// }


namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
   /**
    * The list of the inputs that are never flashed to the session on validation exceptions.
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
    */
   public function register(): void
   {
       $this->reportable(function (Throwable $e) {
           //
       });
   }
}
