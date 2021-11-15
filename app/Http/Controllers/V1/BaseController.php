<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use \Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     *
     * @return JsonResponse
     */
    public function responseSuccess($result = null, $message = ''): JsonResponse
    {
        $response = [
            'status' => true,
            'data'    => $result,
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
     *
     * @return JsonResponse
     */
    public function responseError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }


    /**
     * return Unauthorized response.
     *
     * @param $error
     * @param  int  $code
     *
     * @return JsonResponse
     */
    public function responseUnauthorized($error = 'Unauthorized', $code = 401): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }
}
