<?php

namespace App\Http\Middleware;

use App\Models\Shared\CustomConstants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
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

        if (auth()->user()->role_id !== CustomConstants::ROLE_STAFF) {
            abort(403, CustomConstants::UNAUTHORIZED);
        }

        return $next($request);
    }
}
