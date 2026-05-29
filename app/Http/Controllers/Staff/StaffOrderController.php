<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Staff;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaffOrderController extends Controller
{
    /**
     * Show the order form
     */
    public function create()
    {
        $staff          = Staff::where('user_id', auth()->id())->firstOrFail();
        $items          = Item::where('business_id', $staff->business_id)->where('status_id', 1)->get();
        $paymentMethods = PaymentMethod::all();

        return view('staff.orders.create', compact('staff', 'items', 'paymentMethods'));
    }

    /**
     * Store a new order
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $valid = $this->validateOrderPayload($request);
                if ($valid->fails()) {
                    return redirect()->back()
                        ->withErrors($valid)
                        ->withInput();
                }

                $staff = Staff::where('user_id', auth()->id())->firstOrFail();

                // Generate order number
                $lastOrder   = Order::orderBy('id', 'DESC')->first();
                $number      = $lastOrder ? $lastOrder->auto_number + 1 : 1;
                $orderNumber = 'ORD' . str_pad($number, 4, '0', STR_PAD_LEFT);

                // Create order
                $order = Order::create([
                    'business_id'       => $staff->business_id,
                    'staff_id'          => $staff->id,
                    'payment_method_id' => $request->payment_method_id,
                    'status_id'         => 1,
                    'auto_number'       => $number,
                    'order_number'      => $orderNumber,
                    'table_number'      => $request->table_number,
                    'total'             => 0,
                    'order_date'        => $request->order_date ?? today(),
                    'created_by'        => auth()->id(),
                    'updated_by'        => auth()->id(),
                ]);

                // Create order items
                foreach ($request->items as $item) {
                    $menuItem = Item::findOrFail($item['item_id']);
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'item_id'    => $item['item_id'],
                        'quantity'   => $item['quantity'],
                        'unit_price' => $menuItem->price,
                        'total'      => $menuItem->price * $item['quantity'],
                    ]);
                }

                // Update order total
                $order->update(['total' => $order->items()->sum('total')]);

                return redirect()->route('staff.dashboard')->with('success', 'Order recorded successfully.');
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display staff order history
     */
    public function index()
    {
        $staff = Staff::where('user_id', auth()->id())->firstOrFail();

        $orders = Order::with(['items.item', 'paymentMethod', 'status'])
            ->where('business_id', $staff->business_id)
            ->where('staff_id', $staff->id)
            ->latest()
            ->get();

        return view('staff.orders.index', compact('orders'));
    }

    /**
     * Display a specific order
     *
     * @param Order $order
     */
    public function show(Order $order)
    {
        $order->load(['items.item', 'paymentMethod', 'status']);
        return view('staff.orders.show', compact('order'));
    }

    /**
     * Validate order payload
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateOrderPayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'payment_method_id' => 'required|exists:payment_methods,id',
            'order_date'        => 'required|date',
            'items'             => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);
    }
}
