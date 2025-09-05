<?php
namespace App\Decorators;

use App\Models\Order;

class BaseOrder implements OrderInterface
{
    private Order $order;

    public function __construct(Order $order) { $this->order = $order; }

    public function getTotal(): float
    {
        // Sum product prices (Eloquent)
        return (float) $this->order->products()->sum('price');
    }
}
