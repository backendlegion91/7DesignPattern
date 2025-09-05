<?php
namespace App\Strategies;

class PaymentContext
{
    private PaymentStrategy $strategy;

    public function __construct(PaymentStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function pay(float $amount): string
    {
        return $this->strategy->pay($amount);
    }
}
