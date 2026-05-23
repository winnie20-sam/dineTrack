<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Business;
use App\Models\Shared\CustomConstants;
use App\Models\Status;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BusinessController extends BaseController
{

    /**
     * Display a listing of businesses
     */
    public function index()
    {
        $businesses = Business::with(['status', 'createdBy'])->latest()->get();
        return view('admin.businesses.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new business
     */
    public function create()
    {
        return view('admin.businesses.create');
    }
    /**
     * Store a newly created business
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

                Business::create($this->businessStorePayload($request));

                return $this->success('admin.businesses.index', $this->const::BUSINESS_CREATED);
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Build the business creation payload
     *
     * @param Request $request
     * @return array
     */
    private function businessStorePayload(Request $request): array
    {
        $number = $this->getNumber();
        return [
            'auto_number' => $number['auto_number'],
            'code'        => $number['code'],
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'status_id'   => $this->const::STATUS_ACTIVE,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ];
    }

    /**
     * Get auto-generated code and number for a new business
     *
     * @return array
     */
    private function getNumber(): array
    {
        $number     = 1;
        $lastRecord = Business::orderBy('id', 'DESC')->first();
        if ($lastRecord) {
            $number = $lastRecord->auto_number + 1;
        }
        return ['auto_number' => $number, 'code' => $this->const::BUSINESS_TBL_PREFIX . $number];
    }


    /**
     * Payload validator for creating a business
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateStorePayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:businesses,email',
            'phone' => 'nullable|string|max:20',
        ]);
    }

    /**
     * Show the form for editing a business
     *
     * @param Business $business
     */
    public function edit(Business $business)
    {
        $statuses = Status::all();
        return view('admin.businesses.edit', compact('business', 'statuses'));
    }

    /**
     * Payload validator for updating a business
     *
     * @param Request $request
     * @param Business $business
     * @return \Illuminate\Validation\Validator
     */
    private function validateUpdatePayload(Request $request, Business $business): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:businesses,email,' . $business->id,
            'phone'     => 'nullable|string|max:20',
            'status_id' => 'required|exists:statuses,id',
        ]);
    }

    /**
     * Build the business update payload
     *
     * @param Request $request
     * @return array
     */
    private function businessUpdatePayload(Request $request): array
    {
        return [
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'status_id'  => $request->status_id,
            'updated_by' => auth()->id(),
        ];
    }

    /**
     * Update the specified business
     *
     * @param Request $request
     * @param Business $business
     * @return JsonResponse|RedirectResponse
     */
   public function update(Request $request, Business $business): RedirectResponse
{
    try {
        return DB::transaction(function () use ($request, $business) {
            $valid = $this->validateUpdatePayload($request, $business);
            if ($valid->fails()) {
                return redirect()->back()
                    ->withErrors($valid)
                    ->withInput();
            }

            $business->update($this->businessUpdatePayload($request));

            return $this->success('admin.businesses.index', $this->const::BUSINESS_UPDATED);
        });
    } catch (Exception $e) {
        return redirect()->back()
            ->with('error', $e->getMessage())
            ->withInput();
    }
}

    /**
     * Mark the specified business as deleted
     *
     * @param Business $business
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(Business $business): JsonResponse|RedirectResponse
    {
        try {
            return DB::transaction(function () use ($business) {
                $business->update([
                    'status_id'  => $this->const::STATUS_DELETED,
                    'updated_by' => auth()->id(),
                ]);

                return $this->success('admin.businesses.index', $this->const::BUSINESS_DELETED);
            });
        } catch (Exception $e) {
            return $this->resp->response($this->const::RESPONSE_STATUS_FAILED, $e->getMessage(), '');
        }
    }
}
