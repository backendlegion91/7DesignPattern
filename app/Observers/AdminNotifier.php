<?php
namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class AdminNotifier implements ObserverInterface
{
    public function update(Order $order): void
    {
        // For demo: log; in real app send email/SMS
        Log::info("Admin notified: New order #{$order->id} for user {$order->user_id}");
    }
}
