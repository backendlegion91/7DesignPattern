<?php
namespace App\Adapters;

interface PaymentGatewayInterface
{
    public function pay(float $amount): string;
}