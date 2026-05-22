<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Business;
use App\Models\Item;
use App\Models\Shared\CustomConstants;
use App\Models\Status;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemController extends BaseController
{

    /**
     * Display a listing of items
     */
    public function index()
    {
        $user = auth()->user();

        $query = Item::with(['business', 'status', 'createdBy']);

        if ($user->role_id === $this->const::ROLE_STAFF) {
            $query->where('business_id', $user->business_id);
        }

        $items = $query->latest()->get();
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item
     */
    public function create()
    {
        $businesses = Business::all();
        return view('admin.items.create', compact('businesses'));
    }

    /**
     * Get auto-generated code and number for a new item
     *
     * @return array
     */
    private function getNumber(): array
    {
        $number     = 1;
        $lastRecord = Item::orderBy('id', 'DESC')->first();
        if ($lastRecord) {
            $number = $lastRecord->auto_number + 1;
        }
        return ['auto_number' => $number, 'code' => $this->const::ITEMS_TBL_PREFIX . $number];
    }

    /**
     * Payload validator for creating an item
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateStorePayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
        ]);
    }

    /**
     * Build the item creation payload
     *
     * @param Request $request
     * @return array
     */
    private function itemStorePayload(Request $request): array
    {
        $number = $this->getNumber();
        return [
            'auto_number' => $number['auto_number'],
            'code'        => $number['code'],
            'business_id' => $request->business_id,
            'name'        => $request->name,
            'category'    => $request->category,
            'price'       => $request->price,
            'status_id'   => $this->const::STATUS_ACTIVE,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ];
    }

    /**
     * Store a newly created item
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

                Item::create($this->itemStorePayload($request));

                return $this->success('admin.items.index', $this->const::ITEM_CREATED);
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Show the form for editing an item
     *
     * @param Item $item
     */
    public function edit(Item $item)
    {
        $businesses = Business::all();
        $statuses   = Status::all();
        return view('admin.items.edit', compact('item', 'businesses', 'statuses'));
    }

    /**
     * Payload validator for updating an item
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateUpdatePayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'status_id'   => 'required|exists:statuses,id',
        ]);
    }

    /**
     * Build the item update payload
     *
     * @param Request $request
     * @return array
     */
    private function itemUpdatePayload(Request $request): array
    {
        return [
            'business_id' => $request->business_id,
            'name'        => $request->name,
            'category'    => $request->category,
            'price'       => $request->price,
            'status_id'   => $request->status_id,
            'updated_by'  => auth()->id(),
        ];
    }

    /**
     * Update the specified item
     *
     * @param Request $request
     * @param Item $item
     * @return JsonResponse|RedirectResponse
     */
    public function update(Request $request, Item $item): JsonResponse|RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request, $item) {
                $valid = $this->validateUpdatePayload($request);
                if ($valid->fails()) {
                    return $this->resp->response($this->const::RESPONSE_STATUS_FAILED, $valid->errors()->all(), '');
                }

                $item->update($this->itemUpdatePayload($request));

                return $this->success('admin.items.index', $this->const::ITEM_UPDATED);
            });
        } catch (Exception $e) {
            return $this->resp->response($this->const::RESPONSE_STATUS_FAILED, $e->getMessage(), '');
        }
    }

    /**
     * Mark the specified item as deleted
     *
     * @param Item $item
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(Item $item): JsonResponse|RedirectResponse
    {
        try {
            return DB::transaction(function () use ($item) {
                $item->update([
                    'status_id'  => $this->const::STATUS_DELETED,
                    'updated_by' => auth()->id(),
                ]);

                return $this->success('admin.items.index', $this->const::ITEM_DELETED);
            });
        } catch (Exception $e) {
            return $this->resp->response($this->const::RESPONSE_STATUS_FAILED, $e->getMessage(), '');
        }
    }
}
