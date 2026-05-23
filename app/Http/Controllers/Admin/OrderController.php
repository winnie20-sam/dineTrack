<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Business;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Staff;
use App\Models\Shared\CustomConstants;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    /**
     * Display a listing of orders
     */
    public function index()
    {
        $orders = Order::with(['business', 'staff', 'paymentMethod', 'status', 'items'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $businesses     = Business::all();
        $staff          = Staff::all();
        $items          = Item::all();
        $paymentMethods = PaymentMethod::all();

        return view('admin.orders.create', compact('businesses', 'staff', 'items', 'paymentMethods'));
    }

    /**
     * Store a newly created order
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $valid = $this->validateStorePayload($request);
                if ($valid->fails()) {
                    return redirect()->back()
                        ->withErrors($valid)
                        ->withInput();
                }

                // Create the order
                $order = Order::create($this->orderStorePayload($request));

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
                $order->update([
                    'total' => $order->items()->sum('total'),
                ]);

                return $this->success('admin.orders.index', CustomConstants::ORDER_CREATED);
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified order
     *
     * @param Order $order
     */
    public function show(Order $order)
    {
        $order->load(['business', 'staff', 'paymentMethod', 'status', 'items.item']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     *
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request, $order) {
                $order->update([
                    'status_id'  => $request->status_id,
                    'updated_by' => auth()->id(),
                ]);

                return $this->success('admin.orders.index', $this->const::ORDER_UPDATED);
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cancel the specified order
     *
     * @param Order $order
     * @return RedirectResponse
     */
    public function destroy(Order $order): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($order) {
                $order->update([
                    'status_id'  => $this->const::STATUS_CANCELLED,
                    'updated_by' => auth()->id(),
                ]);

                return $this->success('admin.orders.index', $this->const::ORDER_CANCELLED);
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    // -------------------------------------------------------------------------
    // Private — build order payload
    // -------------------------------------------------------------------------

    /**
     * Build the order creation payload
     *
     * @param Request $request
     * @return array
     */
    private function orderStorePayload(Request $request): array
    {
        $number = $this->getOrderNumber();
        return [
            'business_id'       => $request->business_id,
            'staff_id'          => $request->staff_id,
            'payment_method_id' => $request->payment_method_id,
            'status_id'         => $this->const::STATUS_ACTIVE,
            'auto_number'       => $number['auto_number'],
            'order_number'      => $number['order_number'],
            'table_number'      => $request->table_number,
            'total'             => 0,
            'order_date'        => $request->order_date ?? today(),
            'created_by'        => auth()->id(),
            'updated_by'        => auth()->id(),
        ];
    }

    /**
     * Get auto-generated order number
     *
     * @return array
     */
    private function getOrderNumber(): array
    {
        $number     = 1;
        $lastRecord = Order::orderBy('id', 'DESC')->first();
        if ($lastRecord) {
            $number = $lastRecord->auto_number + 1;
        }
        return [
            'auto_number'  => $number,
            'order_number' => $this->const::ORDER_TBL_PREFIX . str_pad($number, 4, '0', STR_PAD_LEFT),
        ];
    }

    /**
     * Payload validator for creating an order
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateStorePayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'business_id'         => 'required|exists:businesses,id',
            'staff_id'            => 'required|exists:staff,id',
            'payment_method_id'   => 'required|exists:payment_methods,id',
            'table_number'        => 'nullable|string|max:50',
            'order_date'          => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.item_id'     => 'required|exists:items,id',
            'items.*.quantity'    => 'required|integer|min:1',
        ]);
    }
}
