<?php

namespace App\Controllers;

use App\Traits\Aimodel;
use App\Core\DBConnection;
use App\Services\AnalyticsService;
use App\Traits\HttpResponse;
use App\Traits\Weather;

class GPTController {

    use HttpResponse, Aimodel, Weather;
    protected $db;

    public function __construct()
    {
        $this->db = (new DBConnection())->getDB();
    }

    public function getRecommendations()
    {
        //getWeather
        $weatherData = $this->openWeather();

        $weatherCondition = $weatherData->weather[0]->main ?? 'N.A';
        $temperatureKelvin = $weatherData->main->temp ?? 0;
        $temperatureCelsius = round($temperatureKelvin - 273.15, 2);

        $season = match (true) {
            $temperatureCelsius >= 30 => 'summer',
            $temperatureCelsius <= 15 => 'winter',
            default => 'moderate',
        };

        //getReccommendations
        $recommendation = (new AnalyticsService())->getAnalyticsData();
        $jsonData = json_encode($recommendation, JSON_PRETTY_PRINT);

        $prompt = <<<EOT
            Given the following product analytics data:

            Sales Data:
            -----------------
            $jsonData

            Current Weather:
            -----------------
            Condition: {$weatherCondition}
            Temperature: {$temperatureCelsius} °C
            Season: {$season}

            Please recommend:
            1. Which product categories should we promote in this weather and season?
            2. What pricing strategy should we apply to increase revenue?
            3. Are there any specific items that will perform better in the current climate?

            Be concise and insightful.
        EOT;

        $response = $this->callChatGPT($prompt);
        $this->sendSuccess([$response], 'AI recommendation received');
    }
}