<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->paginate(10);

        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = new Product($request->validated());
        $product->name = $request->name;
        $product->description = $request->description;
        $product->type = $request->type;

        if ($request->hasFile('file_path'))  $product->file_path = Storage::putFile("public/products", $request->file('file_path'));

        if ($product->save()) {
            return response()->json([
                "status" => 201,
                "success" => true,
                "message" => "Product created successfully.",
                "data" => [
                    "name" => $product->name,
                    "type" => $product->type,
                    "description" => $product->description
                ],
            ]);
        }

        return response()->json(
            [
                "status" => 500,
                "success" => false,
                "message" => "There was an issue creating the product.",
                "data" => [],
            ]
        );
    }

    public function show(Product $product)
    {
        return response()->json([
            "status" => 200,
            "success" => true,
            "data" => [
                "name" => $product->name,
                "type" => $product->type,
                "description" => $product->description,
                "file_path" => $product->file_path,
            ]
        ]);
    }
}
