<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Response
{
    private $status, $message, $data;

    public static function withData($status, $message, $data, $code)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function withoutData($status, $message, $code)
    {
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $code);
    }
}
