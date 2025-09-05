<?php
namespace App\Decorators;

class DiscountDecorator implements OrderInterface
{
    private OrderInterface $order;
    private float $discount;

    public function __construct(OrderInterface $order, float $discount)
    {
        $this->order = $order;
        $this->discount = $discount;
    }

    public function getTotal(): float
    {
        return max(0, $this->order->getTotal() - $this->discount);
    }
}
