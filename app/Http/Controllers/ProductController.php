<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Factories\ProductFactory;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse { return response()->json(Product::all()); }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price'=> 'required|numeric',
            'type' => 'required|string'
        ]);

        $product = ProductFactory::create($data['type'], $data['name'], (float)$data['price']);
        return response()->json($product, 201);
    }

    public function show($id): JsonResponse { return response()->json(Product::findOrFail($id)); }

    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->update($request->only('name','price','type'));
        return response()->json($product);
    }

    public function destroy($id): JsonResponse
    {
        Product::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
