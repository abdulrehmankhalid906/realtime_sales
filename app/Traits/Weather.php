<?php

namespace App\Traits;

trait Weather {

    public function openWeather()
    {
        $weatherConfig = include(__DIR__ . '/../../config/weather.php');

        $apiKey = $weatherConfig['apiKey'];
        $cityId = $weatherConfig['cityId'];
        
        // print_r($apiKey, $cityId);
        $googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&APPID=" . $apiKey;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        curl_close($ch);
        return json_decode($response);
        //print_r($data);
    }
}