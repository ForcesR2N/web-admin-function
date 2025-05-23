<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('api_token')) {
            return redirect('/api/login');
        }

        // Verify token is still valid by making a request to protected endpoint
        try {
            $fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001/api');
            $response = Http::withToken(Session::get('api_token'))
                ->get("{$fastApiUrl}/user/me");

            if (!$response->successful()) {
                // Token is invalid or expired
                Session::forget(['api_token', 'token_type', 'fastapi_user']);
                return redirect('/api/login')->withErrors([
                    'email' => 'Your session has expired. Please login again.',
                ]);
            }

            // Ensure user is admin
            $userData = $response->json();
            if (!isset($userData['is_admin']) || !$userData['is_admin']) {
                Session::forget(['api_token', 'token_type', 'fastapi_user']);
                return redirect('/api/login')->withErrors([
                    'email' => 'You need admin access to continue',
                ]);
            }

            // Refresh user data in session
            Session::put('fastapi_user', $userData);
        } catch (\Exception $e) {
            Session::forget(['api_token', 'token_type', 'fastapi_user']);
            return redirect('/api/login')->withErrors([
                'email' => 'Authentication error: ' . $e->getMessage(),
            ]);
        }

        return $next($request);
    }
}
