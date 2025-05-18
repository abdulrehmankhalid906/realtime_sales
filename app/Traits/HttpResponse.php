<?php

namespace App\Traits;

trait HttpResponse
{
    public function sendSuccess($data = [], $message = 'Success', $code = 200)
    {
        $this->jsonRespnse(true, $data, null, $message, $code);
    }

    public function sendError($error = null, $message = 'Error', $code = 400)
    {
        $this->jsonRespnse(false, [], $error, $message, $code);
    }

    private function jsonRespnse($status, $data, $error, $message, $code)
    {
        http_response_code($code);
        header('Content-Type: application/json');

        $response = [
            'status' => $status,
            'message' => $message
        ];

        $status ? $response['data'] = $data : $response['error'] = $error;

        echo json_encode($response);
        exit;
    }
}
