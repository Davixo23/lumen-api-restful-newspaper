<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use PDOException;
use Exception;
use Illuminate\Http\Response;// uso del response como respuetas de http
use App\Traits\ApiResponser;// uso del trait creado

class Handler extends ExceptionHandler
{   
    use ApiResponser;// injection del trait en la clase Handler
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // captura de las excepciones
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];

            return $this->errorResponse($message, $code);
        }
        if ($exception instanceof ModelNotFoundException) {// no hay el modelo
            $model = strtolower(class_basename($exception->getModel()));

            return $this->errorResponse("Does not exist any instance of {$model} with the given id", Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof ValidationException) {// error de validacion
            $errors = $exception->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($exception instanceof PDOException) {// error del lenguaje escalado desde unniqueconstraintviolation ya existe una llave duplicada o algun campo con el nombre que se pretende guardar
            $errors = $exception->getMessage();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($exception instanceof Exception) {// cualquier otra excepcion 
            $errors = $exception->getMessage();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // if ($exception instanceof UniqueConstraintViolationException) {// ya existe un alisa con ese nombre
        //     $errors = $exception->getMessage();
        //     return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        if (env('APP_DEBUG', true)) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected error. Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
