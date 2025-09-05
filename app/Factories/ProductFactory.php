<?php
namespace App\Factories;

use App\Models\Product;

class ProductFactory
{
    public static function create(string $type, string $name, float $price): Product
    {
        return Product::create([
            'type' => $type,
            'name' => $name,
            'price' => $price
        ]);
    }
}
