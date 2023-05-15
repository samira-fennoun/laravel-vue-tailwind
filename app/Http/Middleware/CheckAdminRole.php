<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tets = auth()->check();
        Log::debug($tets);
        if (auth()->check()) {
            if (auth()->user()->roles->contains('nom', 'admin')) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Pas autorisé.'], 403);  // 403 Forbidden status code
    }
}
