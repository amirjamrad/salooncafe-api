<?php
namespace App\Helpers;


use Illuminate\Http\JsonResponse;

class Response
{
    public static function successResponse($statusCode = 200,$message,$data = null): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ]);
    }
    public static function errorResponse($statusCode = 404,$message): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
        ]);
    }

}
