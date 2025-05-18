<?php

namespace App\Controllers;

use App\Core\DBConnection;
use App\Traits\HttpResponse;
use WebSocket\Client;
use App\Services\AnalyticsService;

class DashboardController {

    use HttpResponse;
    protected $db;

    public function __construct()
    {
        $this->db = (new DBConnection())->getDB();
    }

    public function createOrder($data)
    {
        $current = date('Y-m-d H:i:s');
        if (empty($data['product_id']) || empty($data['quantity']) || empty($data['price'])) {
            $this->sendError(null, 'Missing required fields', 422);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO orders (product_id, quantity, price, date) VALUES (?, ?, ?, ?)");
        $stmt->bindValue(1, $data['product_id'], SQLITE3_INTEGER);
        $stmt->bindValue(2, $data['quantity'], SQLITE3_INTEGER);
        $stmt->bindValue(3, $data['price'], SQLITE3_FLOAT);
        $stmt->bindValue(4, $current, SQLITE3_TEXT);
        $stmt->execute();

        try {
            $client = new Client("ws://127.0.0.1:8080");
            $client->send(json_encode(['type' => 'new_order']));
            $client->close();
        } catch (\Exception $e) {
            error_log("WebSocket notification failed: " . $e->getMessage());
        }

        $this->sendSuccess([], 'Order successfully added');
    }

    public function getAnalytics()
    {
        $analytics = (new AnalyticsService())->getAnalyticsData();
        $this->sendSuccess([$analytics], 'Analytics fetched successfully');
    }
}