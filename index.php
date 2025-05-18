<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\GPTController;
use App\Controllers\DashboardController;
use App\Controllers\WeatherController;

header('Content-Type: application/json');

$route = $_GET['route'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($route) {
    case 'orders':
        if ($method === 'POST') {
            (new DashboardController())->createOrder($data);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;

    case 'analytics':
        if ($method === 'GET') {
            (new DashboardController())->getAnalytics();
        }
        break;

    case 'recommendations':
        if ($method === 'GET') {
            (new GPTController())->getRecommendations();
        }
        break;

    case 'weather':
        if($method === 'GET')
        {
            (new WeatherController())->getWeather();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'The requested endpoint does not exists']);
}
