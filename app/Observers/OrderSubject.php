<?php
namespace App\Observers;

use App\Models\Order;

class OrderSubject
{
    /** @var ObserverInterface[] */
    private array $observers = [];

    public function attach(ObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    public function notify(Order $order): void
    {
        foreach ($this->observers as $obs) {
            $obs->update($order);
        }
    }
}
