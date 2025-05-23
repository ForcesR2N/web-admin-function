<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Http\Controllers\ApiAuthController;

class ApiBookingController extends Controller
{
    private string $fastApiUrl;

    public function __construct()
    {
        // FastAPI backend URL
        $this->fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001/api');
    }

    // Helper method untuk pemeriksaan autentikasi
    private function checkAuth()
    {
        if (!ApiAuthController::isAuthenticated()) {
            return redirect('/api/login')->withErrors([
                'email' => 'Please login to access this page',
            ]);
        }
        return null;
    }

    // Helper method for authenticated API requests
    private function apiRequest($method, $endpoint, $data = [])
    {
        $token = Session::get('api_token');

        $request = Http::withToken($token);

        switch (strtoupper($method)) {
            case 'GET':
                return $request->get("{$this->fastApiUrl}{$endpoint}");
            case 'POST':
                return $request->post("{$this->fastApiUrl}{$endpoint}", $data);
            case 'PUT':
                return $request->put("{$this->fastApiUrl}{$endpoint}", $data);
            case 'PATCH':
                return $request->patch("{$this->fastApiUrl}{$endpoint}", $data);
            case 'DELETE':
                return $request->delete("{$this->fastApiUrl}{$endpoint}");
            default:
                throw new \Exception("Unsupported HTTP method: {$method}");
        }
    }

    /**
     * Display a listing of rooms from FastAPI.
     */
    public function indexroom()
    {
        // Check auth first
        if ($authRedirect = $this->checkAuth()) {
            return $authRedirect;
        }

        try {
            // Fetch all rooms from FastAPI
            $response = $this->apiRequest('GET', '/room');

            if (!$response->successful()) {
                return view('rooms.index', [
                    'rooms' => [],
                    'error' => 'Failed to fetch rooms from FastAPI: ' . $response->status()
                ]);
            }

            $rooms = $response->json();

            return view('rooms.index', ['rooms' => $rooms]);
        } catch (\Exception $e) {
            Log::error('Error loading rooms from FastAPI: ' . $e->getMessage());

            return view('rooms.index', [
                'rooms' => [],
                'error' => 'Failed to load rooms: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display a specific room from FastAPI.
     */
    public function show($id)
    {
        // Check auth first
        if ($authRedirect = $this->checkAuth()) {
            return $authRedirect;
        }

        try {
            // Fetch room details from FastAPI
            $response = $this->apiRequest('GET', "/room/{$id}");

            if (!$response->successful()) {
                return redirect()->route('rooms.index')
                    ->with('error', 'Failed to fetch room details: ' . $response->status());
            }

            $room = $response->json();

            return view('rooms.show', ['room' => $room]);
        } catch (\Exception $e) {
            Log::error('Error loading room details: ' . $e->getMessage());
            return redirect()->route('rooms.index')
                ->with('error', 'Failed to load room details: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of bookings from FastAPI.
     */
    public function index()
    {
        // Check auth first
        if ($authRedirect = $this->checkAuth()) {
            return $authRedirect;
        }

        try {
            // Fetch all bookings from FastAPI
            $timestamp = time();
            $response = $this->apiRequest('GET', "/booking?nocache={$timestamp}");

            if (!$response->successful()) {
                return view('bookings.index', [
                    'bookings' => [],
                    'stats' => ['total' => 0, 'pending' => 0, 'confirmed' => 0],
                    'error' => 'Failed to fetch bookings from FastAPI: ' . $response->status()
                ]);
            }

            $bookings = $response->json();

            // Format bookings for display
            $formattedBookings = [];
            foreach ($bookings as $booking) {
                // Get place info for each booking
                $placeResponse = $this->apiRequest('GET', "/place/{$booking['place_id']}");
                $place = $placeResponse->successful() ? $placeResponse->json() : null;

                // Format booking data
                $formattedBookings[] = [
                    'id' => $booking['id'],
                    'place_id' => $booking['place_id'],
                    'user_id' => $booking['user_id'],
                    'start_time' => $booking['start_time'],
                    'end_time' => $booking['end_time'],
                    'date' => Carbon::parse($booking['date']),
                    'is_confirmed' => $booking['is_confirmed'] ?? false,
                    'formatted_start_time' => substr($booking['start_time'], 0, 5),
                    'formatted_end_time' => substr($booking['end_time'], 0, 5),
                    'formatted_date' => Carbon::parse($booking['date'])->format('d M Y'),
                    'status_badge' => $booking['is_confirmed']
                        ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Confirmed']
                        : ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
                    'place' => $place,
                    'guest_name' => null, // To be filled if user info is available
                    'guest_email' => null,
                ];

                // Try to get user info for this booking
                try {
                    $userResponse = $this->apiRequest('GET', "/user/me");
                    if ($userResponse->successful()) {
                        $user = $userResponse->json();
                        $formattedBookings[count($formattedBookings) - 1]['guest_name'] = $user['username'] ?? 'Unknown';
                        $formattedBookings[count($formattedBookings) - 1]['guest_email'] = $user['email'] ?? null;
                    }
                } catch (\Exception $e) {
                    // Continue if user info can't be retrieved
                    Log::warning("Could not fetch user info for booking {$booking['id']}: " . $e->getMessage());
                }
            }

            // Calculate stats
            $stats = [
                'total' => count($formattedBookings),
                'pending' => count(array_filter($formattedBookings, fn($b) => !$b['is_confirmed'])),
                'confirmed' => count(array_filter($formattedBookings, fn($b) => $b['is_confirmed'])),
            ];

            return view('bookings.index', [
                'bookings' => $formattedBookings,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading bookings from FastAPI: ' . $e->getMessage());

            return view('bookings.index', [
                'bookings' => [],
                'stats' => ['total' => 0, 'pending' => 0, 'confirmed' => 0],
                'error' => 'Failed to load bookings: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified booking details.
     */
    public function showBooking($id)
    {
        // Check auth first
        if ($authRedirect = $this->checkAuth()) {
            return $authRedirect;
        }

        try {
            // Fetch booking details from FastAPI
            $response = $this->apiRequest('GET', "/booking/{$id}");

            if (!$response->successful()) {
                return redirect()->route('bookings.index')
                    ->with('error', 'Failed to fetch booking details: ' . $response->status());
            }

            $booking = $response->json();

            // Get place info
            $placeResponse = $this->apiRequest('GET', "/place/{$booking['place_id']}");
            $place = $placeResponse->successful() ? $placeResponse->json() : null;

            // Try to get user info
            $userInfo = null;
            try {
                $userResponse = $this->apiRequest('GET', "/user/me");
                if ($userResponse->successful()) {
                    $userInfo = $userResponse->json();
                }
            } catch (\Exception $e) {
                Log::warning("Could not fetch user info for booking {$id}: " . $e->getMessage());
            }

            // Format booking for view
            $formattedBooking = [
                'id' => $booking['id'],
                'place_id' => $booking['place_id'],
                'user_id' => $booking['user_id'],
                'start_time' => $booking['start_time'],
                'end_time' => $booking['end_time'],
                'date' => Carbon::parse($booking['date']),
                'is_confirmed' => $booking['is_confirmed'] ?? false,
                'created_at' => isset($booking['created_at']) ? Carbon::parse($booking['created_at']) : now(),
                'formatted_start_time' => substr($booking['start_time'], 0, 5),
                'formatted_end_time' => substr($booking['end_time'], 0, 5),
                'formatted_date' => Carbon::parse($booking['date'])->format('d M Y'),
                'status_badge' => $booking['is_confirmed']
                    ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Confirmed']
                    : ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
                'place' => $place,
                'user' => $userInfo,
                // Parse additional info
                'guest_name' => $userInfo['username'] ?? 'Unknown',
                'guest_email' => $userInfo['email'] ?? null,
                'capacity' => isset($place['max_capacity']) ? $place['max_capacity'] : 'Unknown',
                'notes' => null, // FastAPI doesn't store notes
                'mobile_info' => [
                    'contact_info' => $userInfo['email'] ?? 'Not provided',
                ],
            ];

            return view('bookings.show', ['booking' => $formattedBooking]);

        } catch (\Exception $e) {
            Log::error('Error loading booking details: ' . $e->getMessage());
            return redirect()->route('bookings.index')
                ->with('error', 'Failed to load booking details: ' . $e->getMessage());
        }
    }

    /**
     * Approve a booking by sending request to FastAPI.
     */
    public function approve($id)
    {
        // Check auth first
        if ($authRedirect = $this->checkAuth()) {
            return $authRedirect;
        }

        try {
            // Get current user ID from session
            $userData = Session::get('fastapi_user');
            $userId = $userData['id'] ?? 1; // Default to 1 if not found

            // Call FastAPI to confirm booking
            $response = $this->apiRequest('PATCH', "/booking/host/confirm/{$id}", [
                'booking_id' => $id,
                'user_id' => $userId
            ]);

            if (!$response->successful()) {
                Log::error('Failed to confirm booking in FastAPI', [
                    'booking_id' => $id,
                    'response' => $response->body()
                ]);

                return redirect()->route('bookings.index')
                    ->with('error', 'Failed to confirm booking: ' . ($response->json()['detail'] ?? 'Unknown error'));
            }

            return redirect()->route('bookings.index')
                ->with('success', "Booking #{$id} confirmed successfully!");

        } catch (\Exception $e) {
            Log::error('Booking approval error', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to confirm booking: ' . $e->getMessage());
        }
    }

    /**
     * Reject/cancel a booking by sending request to FastAPI.
     */
    public function reject($id)
{
    // Check auth first
    if ($authRedirect = $this->checkAuth()) {
        return $authRedirect;
    }

    try {
        // Log untuk debugging
        Log::info('Cancel booking directly', [
            'booking_id' => $id,
            'endpoint' => "/booking/{$id}"
        ]);

        // Panggil FastAPI untuk DELETE booking langsung
        $response = $this->apiRequest('DELETE', "/booking/{$id}");

        // Log respons lengkap
        Log::info('Cancel booking response', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if (!$response->successful()) {
            Log::error('Failed to cancel booking in FastAPI', [
                'booking_id' => $id,
                'response' => $response->body()
            ]);

            return redirect()->route('bookings.index')
                ->with('error', 'Failed to cancel booking: ' . ($response->json()['detail'] ?? 'Unknown error'));
        }

        // Force refresh dengan timestamp
        return redirect()->route('bookings.index', ['refresh' => time()])
            ->with('success', "Booking #{$id} has been cancelled successfully!");

    } catch (\Exception $e) {
        Log::error('Booking cancel error', ['error' => $e->getMessage()]);
        return redirect()->back()
            ->with('error', 'Failed to cancel booking: ' . $e->getMessage());
    }
}
}
