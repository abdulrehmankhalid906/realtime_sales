<?php

namespace App\Traits;

trait Aimodel {

    function callChatGPT($prompt)
    {
        $config = include(__DIR__ . '/../../config/Gpt.php');

        $apiKey = $config['apiKey'];
        $model = $config['model'];
        $apiUrl = 'https://api.openai.com/v1/chat/completions';

        $postData = [
            'model' => $model,
            'messages' => $prompt,
            'temperature' => 0.7,
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer $apiKey"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['choices'][0]['message']['content'] ?? 'Unexpected response: ' . $response;
    }
}