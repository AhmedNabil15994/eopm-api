<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    public function response($result, $message = 'Successfully',$code=200)
    {
        $response = [
            'success' => true,
            'code'  => $code,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    public function error($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function invalidData($error, $errorMessages = [], $code = 422)
    {
        $response = [
            'message' => 'Invalid data.',
            'code'  => $code,
            'errors' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function responsePagination($pagination, $message = 'Successfully')
    {
        return $pagination->additional(['success' => true,'code' => 200, "message" => $message]);
    }

    public function responsePaginationWithData($pagination, $data = [], $message = 'Successfully')
    {
        $additional = array_merge(['success' => true,'code' => 200, "message" => $message], $data);
        return $pagination->additional($additional);
    }
}
