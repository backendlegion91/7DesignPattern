<?php
namespace App\Observers;

use App\Models\Order;

interface ObserverInterface
{
    public function update(Order $order): void;
}
