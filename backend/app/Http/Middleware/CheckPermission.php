<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Usage in routes: ->middleware('permission:vehicles.create')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            return response()->json(['message' => 'Account inactive or unauthenticated.'], 403);
        }

        if (!$user->hasPermission($permission)) {
            return response()->json(['message' => "Forbidden: missing permission '{$permission}'."], 403);
        }

        return $next($request);
    }
}
