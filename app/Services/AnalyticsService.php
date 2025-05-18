<?php

namespace App\Services;

use App\Core\DBConnection;

class AnalyticsService {

    protected $db;
    
    public function __construct()
    {
        $this->db = (new DBConnection())->getDB();
    }

    public function getAnalyticsData()
    {
        $lastMinute = date('Y-m-d H:i:s', strtotime('-1 minute'));

        $totalRevenue = $this->db->querySingle("SELECT SUM(price * quantity) FROM orders") ?? 0;
        $revenueLastMinute = $this->db->querySingle("SELECT SUM(price * quantity) FROM orders WHERE date >= '$lastMinute'") ?? 0;
        $orderCount = $this->db->querySingle("SELECT COUNT(*) FROM orders WHERE date >= '$lastMinute'") ?? 0;

        $topProducts = [];
        $result = $this->db->query("
            SELECT product_id, SUM(quantity) as total_quantity
            FROM orders
            GROUP BY product_id
            ORDER BY total_quantity DESC
            LIMIT 5
        ");
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $topProducts[] = $row;
        }

        return [
            'total_revenue' => (float)$totalRevenue,
            'revenue_last_minute' => (float)$revenueLastMinute,
            'orders_last_minute' => (int)$orderCount,
            'top_products' => $topProducts
        ];
    }
}