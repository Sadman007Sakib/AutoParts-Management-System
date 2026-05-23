<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ================= INVENTORY KPIs =================
        $totalParts = Part::count();
        $totalStock = Part::sum('current_quantity');

        $lowStockCount = Part::whereBetween('current_quantity', [1, 5])->count();
        $outOfStockCount = Part::where('current_quantity', 0)->count();

        // ================= SALES KPIs =================
        $todaySales = Sale::whereDate('created_at', today())->sum('total_amount');

        $monthlySales = Sale::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $topProducts = DB::table('sale_items')
            ->join('parts', 'parts.id', '=', 'sale_items.part_id')
            ->select('parts.name', DB::raw('SUM(sale_items.quantity) as qty'))
            ->groupBy('parts.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // ================= PROFIT (basic version) =================
        $profit = DB::table('sale_items')
            ->join('parts', 'parts.id', '=', 'sale_items.part_id')
            ->select(DB::raw('SUM((sale_items.sold_price - parts.cost_price) * sale_items.quantity) as profit'))
            ->value('profit');

        // ================= STOCK DATA =================
        $stockLabels = Part::latest()->limit(8)->pluck('name');
        $stockValues = Part::latest()->limit(8)->pluck('current_quantity');

        return view('home', compact(
            'user',
            'totalParts',
            'totalStock',
            'lowStockCount',
            'outOfStockCount',
            'todaySales',
            'monthlySales',
            'topProducts',
            'profit',
            'stockLabels',
            'stockValues'
        ));
    }
}
