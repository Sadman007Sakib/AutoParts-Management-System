<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Part;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    /**
     * Daily report (kept for completeness)
     */
    public function daily(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        $sales = Sale::with('seller','items.part')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at','desc')
            ->get();

        $report = $this->computeSalesSummary($sales);

        $threshold = (int) config('shop.low_stock_threshold', 50);
        $lowParts = Part::where('current_quantity','<',$threshold)
                        ->orderBy('current_quantity','asc')->get();

        return view('admin.reports.daily', array_merge($report, [
            'date' => $date,
            'lowParts' => $lowParts,
            'threshold' => $threshold,
            'recentSales' => $sales->take(12),
        ]));
    }

    /**
     * Monthly report — accepts ?month=YYYY-MM (defaults to current month)
     */
    public function monthly(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m')); // e.g. 2025-11
        try {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Exception $e) {
            // fallback to current month if parse fails
            $start = Carbon::now()->startOfMonth();
        }
        $end = (clone $start)->endOfMonth();

        $sales = Sale::with('seller','items.part')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at','desc')
            ->get();

        $report = $this->computeSalesSummary($sales);

        $threshold = (int) config('shop.low_stock_threshold', 50);
        $lowParts = Part::where('current_quantity','<',$threshold)
                        ->orderBy('current_quantity','asc')->get();

        return view('admin.reports.monthly', array_merge($report, [
            'period' => $start->format('F Y'),
            'period_raw' => $start->format('Y-m'),
            'lowParts' => $lowParts,
            'threshold' => $threshold,
            'recentSales' => $sales->take(20),
        ]));
    }

    /**
     * Yearly report — accepts ?year=YYYY (defaults to current year)
     */
    public function yearly(Request $request)
    {
        $year = intval($request->query('year', now()->format('Y')));
        if ($year < 2000 || $year > intval(now()->format('Y')) + 1) {
            $year = now()->format('Y');
        }

        $start = Carbon::create($year, 1, 1)->startOfYear();
        $end = Carbon::create($year, 12, 31)->endOfYear();

        $sales = Sale::with('seller','items.part')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at','desc')
            ->get();

        $report = $this->computeSalesSummary($sales);

        $threshold = (int) config('shop.low_stock_threshold', 50);
        $lowParts = Part::where('current_quantity','<',$threshold)
                        ->orderBy('current_quantity','asc')->get();

        return view('admin.reports.yearly', array_merge($report, [
            'period' => $start->format('Y'),
            'lowParts' => $lowParts,
            'threshold' => $threshold,
            'recentSales' => $sales->take(30),
        ]));
    }

    /**
     * Shared summary logic — returns array of computed totals + sales collection
     */
    protected function computeSalesSummary($sales)
    {
        $totalSalesCount = $sales->count();
        $totalRevenue = 0;    // revenue before tax (after discounts)
        $totalDiscounts = 0;
        $totalTax = 0;
        $grossCost = 0;
        $grossProfit = 0;

        foreach ($sales as $s) {
            $saleSubtotal = $s->subtotal ?? $s->items->sum('line_total');
            $saleDiscount = $s->discount_amount ?? 0;
            $saleTax = $s->tax_amount ?? 0;

            $totalRevenue += ($saleSubtotal - $saleDiscount);
            $totalDiscounts += $saleDiscount;
            $totalTax += $saleTax;

            foreach ($s->items as $it) {
                $part = $it->part;
                $cost = optional($part)->cost_price ?? 0;
                $lineCost = $cost * $it->quantity;
                $lineTotal = $it->line_total;
                $grossCost += $lineCost;
                $grossProfit += ($lineTotal - $lineCost);
            }
        }

        // subtract total discounts to reflect discount impact on profit
        $grossProfitAfterDiscount = $grossProfit - $totalDiscounts;

        return [
            'totalSalesCount' => $totalSalesCount,
            'totalRevenue' => round($totalRevenue, 2),
            'totalDiscounts' => round($totalDiscounts, 2),
            'totalTax' => round($totalTax, 2),
            'grossCost' => round($grossCost, 2),
            'grossProfitBeforeDiscount' => round($grossProfit, 2),
            'grossProfitAfterDiscount' => round($grossProfitAfterDiscount, 2),
            'sales' => $sales,
        ];
    }
}
