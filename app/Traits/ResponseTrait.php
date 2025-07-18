<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

trait ResponseTrait
{
    public function okResponse(string $message, $data = null): JsonResponse
    {
        return $this->successResponse($message, $data, 200);
    }

    public function createdResponse(string $message, $data = null): JsonResponse
    {
        return $this->successResponse($message, $data, 201);
    }

    public function noContentResponse(string $message = 'No content'): JsonResponse
    {
        return response()->json(['message' => $message], 204);
    }

    public function badRequestResponse(string $message = 'Bad request', $errors = null): JsonResponse
    {
        return $this->errorResponse($message, $errors, 400);
    }

    public function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, null, 401);
    }

    public function notFoundResponse(string $message = 'Not found'): JsonResponse
    {
        return $this->errorResponse($message, null, 404);
    }

    public function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, null, 403);
    }

    public function successResponse(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        return $this->jsonResponse(true, $message, $data, $statusCode);
    }

    public function conflictResponse(string $message = 'Conflict', $data = null): JsonResponse
    {
        return $this->errorResponse($message, $data, 409);
    }

    public function errorResponse(string $message, $data = null, int $statusCode = 400): JsonResponse
    {
        return $this->jsonResponse(false, $message, $data, $statusCode);
    }

    private function jsonResponse(bool $status, string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return Response::json($response, $statusCode);
    }
}
