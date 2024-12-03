<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseHelper
{
    public function response($status, $message, $data, $http_code): JsonResponse
    {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $http_code);
    }

    public function successResponse($data, int $http_code = Response::HTTP_OK): JsonResponse
    {
        return $this->response("success", "Request successful", $data, $http_code);
    }

    public function errorResponse($message = "Request failed", int $http_code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $this->response("error", $message, [], $http_code);
    }
}
