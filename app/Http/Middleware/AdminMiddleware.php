<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnums;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = auth('web')->user()->hasRole(RoleEnums::User->value) ?? null;
        if ($role) {
            return redirect()->route('root');
        }
        return $next($request);
    }
}
