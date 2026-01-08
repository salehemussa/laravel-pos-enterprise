<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Modules\Inventory\Http\Requests\AddStockRequest;
use App\Modules\Inventory\Http\Requests\RemoveStockRequest;
use App\Modules\Inventory\Http\Requests\AdjustStockRequest;

use App\Modules\Inventory\DTOs\AddStockData;
use App\Modules\Inventory\DTOs\RemoveStockData;
use App\Modules\Inventory\DTOs\AdjustStockData;

use App\Modules\Inventory\Actions\AddStockAction;
use App\Modules\Inventory\Actions\RemoveStockAction;
use App\Modules\Inventory\Actions\AdjustStockAction;

use App\Modules\Inventory\Repositories\Contracts\StockRepository;
use App\Modules\Inventory\Repositories\Contracts\StockMovementRepository;

use App\Modules\Inventory\Http\Resources\StockResource;
use App\Modules\Inventory\Http\Resources\StockMovementResource;

class InventoryController extends Controller
{
    public function stocks(Request $request, StockRepository $stocks): JsonResponse
    {
        $this->authorize('inventory.view'); // Gate ability

        $perPage = (int) $request->query('per_page', 15);
        $page = $stocks->paginate($perPage);

        return response()->json([
            'data' => StockResource::collection($page),
        ]);
    }

    public function movements(Request $request, StockMovementRepository $movements): JsonResponse
    {
        $this->authorize('inventory.view');

        $perPage = (int) $request->query('per_page', 20);
        $page = $movements->paginate($perPage);

        return response()->json([
            'data' => StockMovementResource::collection($page),
        ]);
    }

    public function add(AddStockRequest $request, AddStockAction $action): JsonResponse
    {
        $this->authorize('inventory.manage');

        $result = $action->execute(
            AddStockData::fromRequest($request),
            (int) auth('api')->id()
        );

        return response()->json([
            'message' => 'Stock added successfully.',
            'stock' => new StockResource($result['stock']->load('product')),
            'movement' => new StockMovementResource($result['movement']->load(['product', 'performer'])),
        ], 201);
    }

    public function remove(RemoveStockRequest $request, RemoveStockAction $action): JsonResponse
    {
        $this->authorize('inventory.manage');

        $result = $action->execute(
            RemoveStockData::fromRequest($request),
            (int) auth('api')->id()
        );

        return response()->json([
            'message' => 'Stock removed successfully.',
            'stock' => new StockResource($result['stock']->load('product')),
            'movement' => new StockMovementResource($result['movement']->load(['product', 'performer'])),
        ], 201);
    }

    public function adjust(AdjustStockRequest $request, AdjustStockAction $action): JsonResponse
    {
        $this->authorize('inventory.manage');

        $result = $action->execute(
            AdjustStockData::fromRequest($request),
            (int) auth('api')->id()
        );

        return response()->json([
            'message' => 'Stock adjusted successfully.',
            'stock' => new StockResource($result['stock']->load('product')),
            'movement' => $result['movement']
                ? new StockMovementResource($result['movement']->load(['product', 'performer']))
                : null,
        ]);
    }
}
