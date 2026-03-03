<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getPaginatedProducts(30);
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_code' => 'required|string|max:50|unique:products,product_code',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'manufacturer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $this->productService->createProduct($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $product = $this->productService->getProductById($id);

        $data = $request->validate([
            'product_code' => 'required|string|max:50|unique:products,product_code,' . $product->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'manufacturer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $this->productService->updateProduct($id, $data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->productService->deleteProduct($id);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
