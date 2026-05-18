<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Business;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends BaseController
{
    /**
     * Display a listing of sales
     */
    public function index()
    {
        $sales = Sale::with(['business', 'staff', 'item', 'createdBy'])->latest()->get();

        return view('admin.sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale
     */
    public function create()
    {
        $businesses = Business::all();
        $staff      = Staff::all();
        $items      = Item::all();

        return view('admin.sales.create', compact('businesses', 'staff', 'items'));
    }

    /**
     * Store a newly created sale
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
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

                Sale::create($this->saleStorePayload($request));

                return $this->success('admin.sales.index', $this->const::SALE_CREATED);
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Build the sale creation payload
     *
     * @param Request $request
     * @return array
     */
    private function saleStorePayload(Request $request): array
    {
        return [
            'business_id' => $request->business_id,
            'staff_id'    => $request->staff_id,
            'item_id'     => $request->item_id,
            'quantity'    => $request->quantity,
            'unit_price'  => $request->unit_price,
            'total'       => $request->quantity * $request->unit_price,
            'sale_date'   => $request->sale_date,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ];
    }

    /**
     * Payload validator for creating a sale
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateStorePayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'staff_id'    => 'required|exists:staff,id',
            'item_id'     => 'required|exists:items,id',
            'quantity'    => 'required|integer|min:1',
            'unit_price'  => 'required|numeric|min:0',
            'sale_date'   => 'required|date',
        ]);
    }
}
