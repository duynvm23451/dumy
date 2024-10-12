<?php

namespace App\Http\Middleware;

use App\trait\FormatResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotTokenMiddleware
{
    use FormatResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Instead of redirecting to a route, return a response for API
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Already authenticated'], 200);
                }

                // For web-based guard, redirect to a default route (e.g., '/home')
                return $this->errorResponse('Invalid credentials');
            }
        }

        return $next($request);
    }
}
