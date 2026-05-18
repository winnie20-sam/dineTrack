<?php

namespace App\Http\Middleware;

use App\Models\Shared\CustomConstants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $adminRoles = [
            CustomConstants::ROLE_ADMIN,
            CustomConstants::ROLE_OWNER,
            CustomConstants::ROLE_MANAGER,
        ];

        if (!in_array(auth()->user()->role_id, $adminRoles)) {
            abort(403, CustomConstants::UNAUTHORIZED);
        }

        return $next($request);
    }
}
