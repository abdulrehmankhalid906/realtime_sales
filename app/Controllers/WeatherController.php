<?php

namespace App\Controllers;

use App\Traits\Weather;

class WeatherController {

    use Weather;
    public function getWeather() {
       $weatherData = $this->openWeather();

        $weatherCondition = $weatherData->weather[0]->main ?? 'N.A';
        $temperatureKelvin = $weatherData->main->temp ?? 0;
        $temperatureCelsius = round($temperatureKelvin - 273.15, 2);

        $season = match (true) {
            $temperatureCelsius >= 30 => 'Summer',
            $temperatureCelsius <= 15 => 'Winter',
            default => 'moderate',
        };

        print_r($season);
    }
}