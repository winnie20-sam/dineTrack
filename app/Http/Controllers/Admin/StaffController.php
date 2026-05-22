<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Business;
use App\Models\Shared\CustomConstants;
use App\Models\Staff;
use App\Models\Status;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffController extends BaseController
{

    /**
     * Display a listing of staff members
     */
    public function index()
    {
        $staff = Staff::with(['business', 'status', 'createdBy'])->latest()->get();
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member
     */
    public function create()
    {
        $businesses = Business::all();
        $statuses   = Status::all();
        return view('admin.staff.create', compact('businesses', 'statuses'));
    }



    /**
     * Store a newly created staff member in the staff and users tables
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

                $tempPassword = Str::random(10);

                $user = User::create($this->userStorePayload($request, $tempPassword));
                Staff::create($this->staffStorePayload($request, $user->id));

                return $this->success('admin.staff.index', CustomConstants::staffCreatedWithPassword($tempPassword));
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Build the staff creation payload
     *
     * @param Request $request
     * @param int $userId
     * @return array
     */
    private function staffStorePayload(Request $request, int $userId): array
    {
        return [
            'business_id' => $request->business_id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'status_id'   => $this->const::STATUS_ACTIVE,
            'user_id'     => $userId,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ];
    }


    /**
     * Payload validator for creating a staff member
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateStorePayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email|unique:staff,email',
            'phone'       => 'nullable|string|max:20',
        ]);
    }

    /**
     * Build the user creation payload
     *
     * @param Request $request
     * @param string $tempPassword
     * @return array
     */
    private function userStorePayload(Request $request, string $tempPassword): array
    {
        return [
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($tempPassword),
            'role_id'   => $this->const::ROLE_STAFF,
            'business_id' => $request->business_id,
            'status_id' => $this->const::STATUS_ACTIVE,
        ];
    }

    /**
     * Show the form for editing a staff member
     *
     * @param Staff $staff
     */
    public function edit(Staff $staff)
    {
        $businesses = Business::all();
        $statuses   = Status::all();
        return view('admin.staff.edit', compact('staff', 'businesses', 'statuses'));
    }

    /**
     * Payload validator for updating a staff member
     *
     * @param Request $request
     * @param Staff $staff
     * @return \Illuminate\Validation\Validator
     */
    private function validateUpdatePayload(Request $request, Staff $staff): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $staff->user_id . '|unique:staff,email,' . $staff->id,
            'phone'       => 'nullable|string|max:20',
            'status_id'   => 'required|exists:statuses,id',
        ]);
    }

    /**
     * Build the staff update payload
     *
     * @param Request $request
     * @return array
     */
    private function staffUpdatePayload(Request $request): array
    {
        return [
            'business_id' => $request->business_id,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'status_id'   => $request->status_id,
            'updated_by'  => auth()->id(),
        ];
    }

    /**
     * Update the specified staff member in the staff and users tables
     *
     * @param Request $request
     * @param Staff $staff
     * @return JsonResponse|RedirectResponse
     */
    public function update(Request $request, Staff $staff): JsonResponse|RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request, $staff) {
                $valid = $this->validateUpdatePayload($request, $staff);
                if ($valid->fails()) {
                    return $this->resp->response($this->const::RESPONSE_STATUS_FAILED, $valid->errors()->all(), '');
                }

                $staff->update($this->staffUpdatePayload($request));

                if ($staff->user) {
                    $staff->user->update([
                        'name'  => $request->name,
                        'email' => $request->email,
                    ]);
                }

                return $this->success('admin.staff.index', $this->const::STAFF_UPDATED);
            });
        } catch (Exception $e) {
            return $this->resp->response($this->const::RESPONSE_STATUS_FAILED, $e->getMessage(), '');
        }
    }

    /**
     * Mark the specified staff member and their user account as deleted
     *
     * @param Staff $staff
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(Staff $staff): JsonResponse|RedirectResponse
    {
        try {
            return DB::transaction(function () use ($staff) {
                $staff->update([
                    'status_id'  => $this->const::STATUS_DELETED,
                    'updated_by' => auth()->id(),
                ]);

                if ($staff->user) {
                    $staff->user->update([
                        'status_id' => $this->const::STATUS_DELETED,
                    ]);
                }

                return $this->success('admin.staff.index', $this->const::STAFF_DELETED);
            });
        } catch (Exception $e) {
            return $this->resp->response($this->const::RESPONSE_STATUS_FAILED, $e->getMessage(), '');
        }
    }
}
