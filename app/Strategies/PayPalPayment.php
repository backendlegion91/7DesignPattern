<?php
namespace App\Strategies;

class PayPalPayment implements PaymentStrategy
{
    public function pay(float $amount): string
    {
        return "Paid {$amount} via PayPal";
    }
}
