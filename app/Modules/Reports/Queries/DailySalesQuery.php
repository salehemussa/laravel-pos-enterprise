<?php

namespace App\Modules\Reports\Queries;

use Illuminate\Support\Facades\DB;

class DailySalesQuery
{
    public function run(string $from, string $to): array
    {
        // Group by date for charting (daily totals)
        $rows = DB::table('sales')
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw('COUNT(*) as sales_count')
            ->selectRaw('COALESCE(SUM(total_cents),0) as total_cents')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->where('status', 'completed')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day')
            ->get();

        return $rows->map(fn($r) => [
            'day' => $r->day,
            'sales_count' => (int)$r->sales_count,
            'total_cents' => (int)$r->total_cents,
        ])->all();
    }
}
