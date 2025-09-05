<?php
namespace App\Services;

use App\Models\Order;

class OrderService
{
    private static ?OrderService $instance = null;
    private array $orders = [];

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): OrderService
    {
        return self::$instance ??= new OrderService();
    }

    public function addOrder(Order $order): void { $this->orders[] = $order; }
    public function getOrders(): array { return $this->orders; }
}
