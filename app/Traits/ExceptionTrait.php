<?php

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionTrait
{
    public function apiException($request, $e)
    {
        if ($this->isAuthentication($e)) {
            return $this->AuthResponse($e);
        }

        if ($this->isModel($e)) {
            return $this->ModelResponse($e);
        }

        if ($this->isHttp($e)) {
            return $this->HttpResponse($e);
        }

        return parent::render($request, $e);
    }

    protected function isAuthentication($e)
    {
        return $e instanceof AuthenticationException;
    }

    protected function isModel($e)
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isHttp($e)
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function AuthResponse($e)
    {
        return response()->json([
            'errors' => 'Unauthenticated'
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function ModelResponse($e)
    {
        return response()->json([
            'errors' => 'Not found'
        ], Response::HTTP_NOT_FOUND);
    }

    protected function HttpResponse($e)
    {
        return response()->json([
            'errors' => 'Incorrect route'
        ], Response::HTTP_NOT_FOUND);
    }
}
