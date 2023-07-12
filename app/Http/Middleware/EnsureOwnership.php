<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $resourceId = $request->route('id');
    
        if($user && $user->id === Event::find($resourceId)->user_id) {
            return $next($request);
        }


        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
