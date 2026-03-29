<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Send a successful response.
     */
    protected function sendResponse(mixed $data, string $message = "Success", int $code = 200): JsonResponse
    {
        return response()->json([
            "status"  => true,
            "message" => $message,
            "data"    => $data,
        ], $code);
    }

    /**
     * Send an error response.
     */
    protected function sendError(string $message, int $code = 404, array $data = []): JsonResponse
    {
        $response = [
            "status"  => false,
            "message" => $message,
        ];

        if (!empty($data)) {
            $response["data"] = $data;
        }

        return response()->json($response, $code);
    }
}
