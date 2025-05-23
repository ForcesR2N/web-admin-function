<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ApiAuthController extends Controller
{
    private string $fastApiUrl;

    public function __construct()
    {
        $this->fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001/api');
    }

    public function showLogin()
    {
        return view('auth.api-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            // Call FastAPI login endpoint
            $response = Http::asForm()->post("{$this->fastApiUrl}/login", [
                'username' => $request->email,
                'password' => $request->password,
            ]);

            if (!$response->successful()) {
                Log::error('Login failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return back()->withErrors([
                    'email' => 'Invalid credentials or API error',
                ])->withInput($request->only('email'));
            }

            $data = $response->json();

            if (!isset($data['access_token'])) {
                return back()->withErrors([
                    'email' => 'Invalid response from authentication server',
                ])->withInput($request->only('email'));
            }

            // Store token in session
            Session::put('api_token', $data['access_token']);
            Session::put('token_type', $data['token_type'] ?? 'bearer');

            // Fetch user info to confirm if user is admin
            $userResponse = Http::withToken($data['access_token'])
                ->get("{$this->fastApiUrl}/user/me");

            if ($userResponse->successful()) {
                $userData = $userResponse->json();

                // Check if user is admin
                if (isset($userData['is_admin']) && $userData['is_admin']) {
                    Session::put('fastapi_user', $userData);
                    return redirect()->intended('/dashboard');
                } else {
                    // Logout if not admin
                    Session::forget(['api_token', 'token_type']);
                    return back()->withErrors([
                        'email' => 'You need admin access to continue',
                    ])->withInput($request->only('email'));
                }
            }

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);

            return back()->withErrors([
                'email' => 'An error occurred during login: ' . $e->getMessage(),
            ])->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        Session::forget(['api_token', 'token_type', 'fastapi_user']);
        return redirect('/api/login');
    }

    // Helper method to check if user is authenticated
    public static function isAuthenticated()
    {
        if (!Session::has('api_token')) {
            return false;
        }

        try {
            $fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001/api');
            $response = Http::withToken(Session::get('api_token'))
                ->get("{$fastApiUrl}/user/me");

            if (!$response->successful()) {
                Session::forget(['api_token', 'token_type', 'fastapi_user']);
                return false;
            }

            $userData = $response->json();
            if (!isset($userData['is_admin']) || !$userData['is_admin']) {
                Session::forget(['api_token', 'token_type', 'fastapi_user']);
                return false;
            }

            // Refresh user data
            Session::put('fastapi_user', $userData);
            return true;
        } catch (\Exception $e) {
            Log::error('Auth check error', ['error' => $e->getMessage()]);
            Session::forget(['api_token', 'token_type', 'fastapi_user']);
            return false;
        }
    }
}
