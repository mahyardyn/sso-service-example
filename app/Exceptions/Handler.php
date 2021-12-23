<?php

namespace App\Exceptions;

use App\Lib\Logger\Logger;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $e
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    private function is_serialized($string): bool
    {
        return (@unserialize($string) !== false);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse
    {
        return parent::render($request, $e);
    }

    /**
     * Change default locale to json locale
     *
     * @return void
     */
    protected function setDefaultLocale(): void
    {
        app('translator')->setLocale(config('app.json_locale'));
    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     */
    protected function prepareJsonResponse($request, Throwable $e): JsonResponse
    {
        // Grab the HTTP status code from the Exception
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

        // Select a message relative to error code
        $statusText = match ($statusCode) {
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Resource Not Found',
            405 => 'Method Not Allowed',
            422 => 'Request unable to be processed',
            429 => 'Please try again in two minutes',
            400 => 'The information entered is incorrect',
            default => 'Whoops, looks like something went wrong',
        };

        // Define the response
        $response = [
            'success' => false,
            'errors' => $statusText,
        ];

        // If the app is in debug modeْ
        if (config('app.debug')) {
            if (get_class($e) === 'Error') {
                $response['file'] = $e->getFile();
                $response['line'] = $e->getLine();
            }

            $response['errors'] = $e->getMessage() ?
                $this->is_serialized($e->getMessage()) ?
                    unserialize($e->getMessage()) : [$e->getMessage()]
                : [$statusText];
        }

        // Generate log
        Logger::set('error', 'exceptions', $response['errors'], $request->url());

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Translate messages to APP_LOCALE language
        $response['errors'] = array_map(function ($msg) {
            return __($msg);
        }, $response['errors']);

        // Return a JSON response with the response array and status code
        return new JsonResponse($response, $statusCode);
    }
}
