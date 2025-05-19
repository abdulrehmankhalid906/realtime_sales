<?php

namespace App\Traits;

trait Aimodel {

    function callChatGPT($prompt)
    {
        $config = include(__DIR__ . '/../../config/Gpt.php');

        $apiKey = $config['apiKey'];
        $model = $config['model'];
        $apiUrl = 'https://api.mistral.ai/v1/chat/completions';

        $postData = [
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ];

        // echo '<pre>';
        // print_r($postData);
        // echo '</pre>';
        // exit;

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            "Authorization: Bearer $apiKey"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        // echo '<pre>';
        // print_r($response);
        // echo '</pre>';
        // exit;

        $result = json_decode($response, true);

        if (isset($result['choices'][0]['message']['content'])) {
            return $result['choices'][0]['message']['content'];
        }

        return 'Unexpected response: ' . $response;
    }
}