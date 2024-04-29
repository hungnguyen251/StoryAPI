<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class AppBaseController extends Controller
{
    public function sendResponse($data, $message, $statusCode = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($response, $statusCode);
    }

    public function sendError($error, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return response()->json($error, $statusCode);
    }
}
