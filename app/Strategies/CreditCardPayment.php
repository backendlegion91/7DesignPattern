<?php
namespace App\Strategies;

class CreditCardPayment implements PaymentStrategy
{
    public function pay(float $amount): string
    {
        return "Paid {$amount} via Credit Card";
    }
}
