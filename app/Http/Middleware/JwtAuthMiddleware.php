<?php

namespace App\Http\Middleware;

use App\Constants;
use Closure;
use Config;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->bearerToken() == null)
            return response()->json(["success" => false, "status" => "error", "message" => "Token missing in header!"]);
        $signer = new Sha256();
        $token = (new Parser())->parse((string)$request->bearerToken());

        $extra = [];

        if ($token->verify($signer, Config::get("jwt.secret")))
            return $next($request->merge([
                Constants::CURRENT_USER_ID_KEY => $token->getClaim("user_id"),
                Constants::CURRENT_USERNAME_KEY => $token->getClaim("username"),
                Constants::CURRENT_USER_ROLE_KEY => $token->getClaim("user_role_name"),
                Constants::CURRENT_USER_ROLE_ID_KEY => $token->getClaim("user_role_id")
            ]));
        else
            return response()->json([["success" => false, "status" => "error", "message" => "Invalid token!"]]);
    }
}
