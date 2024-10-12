<?php

namespace App\Exceptions;

use App\Http\Responses\ResponsesInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiHandler extends ExceptionHandler
{
    /**
     * @var ResponsesInterface
     */
    private $apiResponder;

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
     * ApiHandler constructor.
     *
     * @param Container $container
     * @param ResponsesInterface $apiResponder
     *
     * @return void
     */
    public function __construct(Container $container, ResponsesInterface $apiResponder)
    {
        parent::__construct($container);
        $this->apiResponder = $apiResponder;
    }


    /**
     * Report or log an exception.
     *
     * @param  \Throwable $e
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request $request
     * @param  Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if (($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException)) {
            return $this->apiResponder->respondNotFound();
        } elseif ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
            return $this->apiResponder->respondAuthorizationError();
        }

        $e = $this->prepareException($e);

        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        return $request->expectsJson() || $request->isJson() || $request->is('api/*')
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException $e
     * @param  Request $request
     * @return Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->response ?? $e->validator->errors()->getMessages();
        $errors = call_user_func_array('array_merge', array_values($errors));

        return $this->apiResponder->respondWithValidationError($errors[0]);
    }


    /**
     * Converts an authenticated exception into an unauthenticated response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->apiResponder->respondAuthenticationError();
    }
}
