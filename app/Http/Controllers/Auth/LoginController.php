<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Shared\CustomConstants;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect user to the correct dashboard based on their role
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        $roleId = auth()->user()->role_id;

        return match ($roleId) {
            CustomConstants::ROLE_STAFF   => route('staff.dashboard'),
            CustomConstants::ROLE_MANAGER => route('dashboard'),
            CustomConstants::ROLE_OWNER   => route('dashboard'),
            CustomConstants::ROLE_ADMIN   => route('dashboard'),
            default                       => route('login'),
        };
    }
}
