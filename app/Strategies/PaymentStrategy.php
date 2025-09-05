<?php
namespace App\Strategies;

interface PaymentStrategy
{
    public function pay(float $amount): string;
}
