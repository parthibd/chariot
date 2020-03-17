<?php

namespace App\Http\Middleware;

use App\Constants;
use Closure;

class AccessControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {

        if (in_array($request->input(Constants::CURRENT_USER_ROLE_KEY), $roles))
            return $next($request);
        else
            return response()->json(["success" => false, "status" => "error", "message" => "Unauthorized action!"], 401);

    }
}
