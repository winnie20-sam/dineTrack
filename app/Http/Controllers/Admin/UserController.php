<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of all users
     */
    public function index()
    {
        $users = User::with(['role', 'status', 'business'])->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing a user
     *
     * @param User $user
     */
    public function edit(User $user)
    {
        $roles    = Role::all();
        $statuses = Status::all();
        return view('admin.users.edit', compact('user', 'roles', 'statuses'));
    }

    /**
     * Update the specified user's role and status
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request, $user) {
                $valid = $this->validateUpdatePayload($request, $user);
                if ($valid->fails()) {
                    return redirect()->back()
                        ->withErrors($valid)
                        ->withInput();
                }

                $user->update([
                    'role_id'   => $request->role_id,
                    'status_id' => $request->status_id,
                    'updated_at' => now(),
                ]);

                return $this->success('admin.users.index', $this->const::USER_UPDATED);
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Payload validator for updating a user
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Validation\Validator
     */
    private function validateUpdatePayload(Request $request, User $user): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'role_id'   => 'required|exists:roles,id',
            'status_id' => 'required|exists:statuses,id',
        ]);
    }
}
