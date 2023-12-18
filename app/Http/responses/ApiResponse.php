<?php

namespace App\Http\Responses;

class ApiResponse{

    public static function success($message = 'success', $statusCode = 200, $data = []) 
    {
        return response()->json([
            'status'=> true,
            'message'=> $message, 
            'data'=> $data
        ], $statusCode);
    }

    public static function error($message = 'error', $statusCode = 501, $errors = [])
    {
        return response()->json([
            'status'=> false,
            'message'=> $message, 
            'errors'=> $errors
        ], $statusCode);
    }

}