<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckLoggedUserPermissions
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->is_admin) {
            return $next($request);
        }

        if ($request->user()->permissions()->contains(Route::currentRouteName())) {
            return $next($request);
        }

        return response()->json(['message' => 'Acesso negado'], 403);
    }
}
