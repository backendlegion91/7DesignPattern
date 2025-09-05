<?php
namespace App\Adapters;

class PaymentAdapter implements PaymentGatewayInterface
{
    private LegacyPaymentGateway $legacy;

    public function __construct(LegacyPaymentGateway $legacy)
    {
        $this->legacy = $legacy;
    }

    public function pay(float $amount): string
    {
        // adapt call
        return $this->legacy->makePayment($amount);
    }
}
