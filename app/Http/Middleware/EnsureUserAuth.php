<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->route('id');
        $currentUser = Auth::user();

        if($currentUser && $currentUser->id === $userId) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
