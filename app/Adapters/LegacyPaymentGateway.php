<?php
namespace App\Adapters;

class LegacyPaymentGateway
{
    // old method signature
    public function makePayment($amount)
    {
        return "Paid {$amount} via LegacyGateway";
    }
}
