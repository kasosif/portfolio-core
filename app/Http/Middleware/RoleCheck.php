<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @param mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        if (auth('api')->user()->hasRole($roles) ) {
            return $next($request);
        }
        return response()->json([
            "code" => 401,
            "message" =>"Unauthorized",
            "resultType" => "ERROR",
            "result" => null
        ],401);

    }
}
