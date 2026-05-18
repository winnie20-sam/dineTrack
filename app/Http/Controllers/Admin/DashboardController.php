<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Sale;
use App\Models\Staff;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        // Overall totals
        $totalRevenue    = Sale::whereDate('sale_date', today())->sum('total');
        $totalBusinesses = Business::count();
        $totalStaff      = Staff::count();
        $totalItems      = Item::count();

        // Per business breakdown
        $businesses = Business::with(['status'])
            ->withCount('sales')
            ->get()
            ->map(function ($business) {
                $business->today_revenue = Sale::where('business_id', $business->id)
                    ->whereDate('sale_date', today())
                    ->sum('total');
                $business->today_sales = Sale::where('business_id', $business->id)
                    ->whereDate('sale_date', today())
                    ->count();
                $business->top_item = Sale::where('business_id', $business->id)
                    ->whereDate('sale_date', today())
                    ->with('item')
                    ->selectRaw('item_id, SUM(quantity) as total_qty')
                    ->groupBy('item_id')
                    ->orderByDesc('total_qty')
                    ->first();
                return $business;
            });

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalBusinesses',
            'totalStaff',
            'totalItems',
            'businesses'
        ));
    }
}
