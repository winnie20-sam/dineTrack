<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Staff;
// use App\Models\Sale;
// use App\Models\Item;

class StaffDashboardController extends Controller
{
    /**
     * Get the authenticated staff member
     */
    private function getStaff(): Staff
    {
        return Staff::where('user_id', auth()->id())->firstOrFail();
    }

    /**
     * Display staff dashboard
     */
    public function index()
    {
        $staff = $this->getStaff();

        $todayOrders = Order::with(['items.item', 'paymentMethod'])
            ->where('business_id', $staff->business_id)
            ->where('staff_id', $staff->id)
            ->whereDate('order_date', today())
            ->latest()
            ->get();

        $totalToday  = $todayOrders->sum('total');
        $todayCount  = $todayOrders->count();
        $totalOrders = Order::where('staff_id', $staff->id)->count();

        return view('staff.dashboard', compact(
            'staff',
            'todayOrders',
            'totalToday',
            'todayCount',
            'totalOrders'
        ));
    }

    // -------------------------------------------------------------------------
    // Legacy sale methods — kept for reference, replaced by orders
    // -------------------------------------------------------------------------

    // public function createSale()
    // {
    //     $staff      = $this->getStaff();
    //     $items      = Item::where('business_id', $staff->business_id)->get();
    //     $todaySales = Sale::where('staff_id', $staff->id)
    //         ->whereDate('sale_date', today())
    //         ->with('item')
    //         ->latest()
    //         ->get();
    //     $todayTotal = Sale::where('staff_id', $staff->id)
    //         ->whereDate('sale_date', today())
    //         ->sum('total');

    //     return view('staff.sale-create', compact('staff', 'items', 'todaySales', 'todayTotal'));
    // }

    // public function recordSale(Request $request)
    // {
    //     $staff = $this->getStaff();

    //     $request->validate([
    //         'item_id'    => 'required|exists:items,id',
    //         'quantity'   => 'required|integer|min:1',
    //         'unit_price' => 'required|numeric|min:0',
    //         'sale_date'  => 'required|date',
    //     ]);

    //     Sale::create([
    //         'business_id' => $staff->business_id,
    //         'staff_id'    => $staff->id,
    //         'item_id'     => $request->item_id,
    //         'quantity'    => $request->quantity,
    //         'unit_price'  => $request->unit_price,
    //         'total'       => $request->quantity * $request->unit_price,
    //         'sale_date'   => $request->sale_date,
    //         'created_by'  => auth()->id(),
    //         'updated_by'  => auth()->id(),
    //     ]);

    //     return redirect()->route('staff.sale.create')->with('success', 'Sale recorded successfully.');
    // }
}
