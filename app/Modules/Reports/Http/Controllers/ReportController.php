<?php

namespace App\Modules\Reports\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use App\Modules\Reports\Http\Requests\SalesSummaryRequest;
use App\Modules\Reports\Http\Requests\DateRangeRequest;

use App\Modules\Reports\Queries\SalesSummaryQuery;
use App\Modules\Reports\Queries\DailySalesQuery;
use App\Modules\Reports\Queries\TopProductsQuery;
use App\Modules\Reports\Queries\InventoryValuationQuery;

use App\Modules\Reports\Http\Resources\SalesSummaryResource;
use App\Modules\Reports\Http\Resources\TopProductResource;
use App\Modules\Reports\Http\Resources\InventoryValuationResource;

class ReportController extends Controller
{
    public function salesSummary(SalesSummaryRequest $request, SalesSummaryQuery $query): JsonResponse
    {
        $v = $request->validated();

        $data = $query->run(
            from: $v['from'],
            to: $v['to'],
            soldBy: $v['sold_by'] ?? null,
            paymentStatus: $v['payment_status'] ?? null,
            status: $v['status'] ?? null,
        );

        return response()->json([
            'data' => SalesSummaryResource::make($data),
        ]);
    }

    public function dailySales(DateRangeRequest $request, DailySalesQuery $query): JsonResponse
    {
        $v = $request->validated();

        return response()->json([
            'data' => $query->run($v['from'], $v['to']),
        ]);
    }

    public function topProducts(DateRangeRequest $request, TopProductsQuery $query): JsonResponse
    {
        $v = $request->validated();

        // Allow ?limit=10
        $limit = (int) request()->query('limit', 10);
        $limit = max(1, min(50, $limit));

        return response()->json([
            'data' => TopProductResource::collection(
                $query->run($v['from'], $v['to'], $limit)
            ),
        ]);
    }

    public function inventoryValuation(InventoryValuationQuery $query): JsonResponse
    {
        return response()->json([
            'data' => InventoryValuationResource::make($query->run()),
        ]);
    }
}
