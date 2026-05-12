<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function successResponse($data = null, $message = 'Success')
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null
        ], 200);
    }

    public function errorResponse($message = 'Error', $errors = [], $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors
        ], $code);
    }
}
