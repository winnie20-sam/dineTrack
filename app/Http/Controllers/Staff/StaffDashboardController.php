<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Item;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    private function getStaff()
    {
        return Staff::where('user_id', auth()->id())->firstOrFail();
    }

    public function index()
    {
        $staff      = $this->getStaff();
        $todayCount = Sale::where('staff_id', $staff->id)
            ->whereDate('sale_date', today())
            ->count();
        $todayTotal = Sale::where('staff_id', $staff->id)
            ->whereDate('sale_date', today())
            ->sum('total');
        $totalSales = Sale::where('staff_id', $staff->id)->count();

        return view('staff.dashboard', compact('staff', 'todayCount', 'todayTotal', 'totalSales'));
    }

    public function createSale()
    {
        $staff      = $this->getStaff();
        $items      = Item::where('business_id', $staff->business_id)->get();
        $todaySales = Sale::where('staff_id', $staff->id)
            ->whereDate('sale_date', today())
            ->with('item')
            ->latest()
            ->get();
        $todayTotal = Sale::where('staff_id', $staff->id)
            ->whereDate('sale_date', today())
            ->sum('total');

        return view('staff.sale-create', compact('staff', 'items', 'todaySales', 'todayTotal'));
    }

    public function recordSale(Request $request)
    {
        $staff = $this->getStaff();

        $request->validate([
            'item_id'    => 'required|exists:items,id',
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'sale_date'  => 'required|date',
        ]);

        Sale::create([
            'business_id' => $staff->business_id,
            'staff_id'    => $staff->id,
            'item_id'     => $request->item_id,
            'quantity'    => $request->quantity,
            'unit_price'  => $request->unit_price,
            'total'       => $request->quantity * $request->unit_price,
            'sale_date'   => $request->sale_date,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('staff.sale.create')->with('success', 'Sale recorded successfully.');
    }
}
