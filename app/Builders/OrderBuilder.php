<?php
namespace App\Builders;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class OrderBuilder
{
    private Order $order;
    private array $products = [];

    public function __construct()
    {
        $this->order = new Order();
    }

    public function setUser(User $user): self
    {
        $this->order->user_id = $user->id;
        return $this;
    }

    public function setPaymentMethod(string $method): self
    {
        $this->order->payment_method = $method;
        return $this;
    }

    public function addProduct(Product $product): self
    {
        $this->products[] = $product;
        return $this;
    }

    public function build(): Order
    {
        $this->order->save();
        foreach ($this->products as $p) {
            $this->order->products()->attach($p->id);
        }
        return $this->order->refresh();
    }
}
