<?php

namespace App\Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Modules\Products\Http\Requests\StoreProductRequest;
use App\Modules\Products\Http\Requests\UpdateProductRequest;
use App\Modules\Products\Http\Resources\ProductResource;

use App\Modules\Products\DTOs\CreateProductData;
use App\Modules\Products\DTOs\UpdateProductData;

use App\Modules\Products\Actions\CreateProductAction;
use App\Modules\Products\Actions\UpdateProductAction;
use App\Modules\Products\Actions\DeleteProductAction;
use App\Modules\Products\Repositories\Contracts\ProductRepository;

class ProductController extends Controller
{
    public function index(Request $request, ProductRepository $products): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $perPage = (int) $request->query('per_page', 15);

        // Later you can add search filters in repository (sku/name)
        $page = $products->paginate($perPage);

        return response()->json([
            'data' => ProductResource::collection($page),
        ]);
    }

    public function store(StoreProductRequest $request, CreateProductAction $create): JsonResponse
    {
        $this->authorize('create', Product::class);

        $userId = (int) auth('api')->id();

        $product = $create->execute(
            CreateProductData::fromRequest($request),
            $userId
        );

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => new ProductResource($product),
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $update): JsonResponse
    {
        $this->authorize('update', $product);

        $updated = $update->execute($product, UpdateProductData::fromRequest($request));

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($updated),
        ]);
    }

    public function destroy(Product $product, DeleteProductAction $delete): JsonResponse
    {
        $this->authorize('delete', $product);

        $delete->execute($product);

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
}
