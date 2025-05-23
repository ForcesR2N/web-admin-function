<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiBookingController extends Controller
{
    private string $fastApiUrl;

    public function __construct()
    {
        // FastAPI backend URL
        $this->fastApiUrl = env('FASTAPI_URL', 'http://127.0.0.1:8001/api');
    }

    /**
     * Display a listing of rooms from FastAPI.
     */
    public function indexroom()
    {
        try {
            // Fetch all rooms from FastAPI
            $response = Http::get("{$this->fastApiUrl}/room");

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
        try {
            // Fetch room details from FastAPI
            $response = Http::get("{$this->fastApiUrl}/room/{$id}");

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
        try {
            // Fetch all bookings from FastAPI
            $response = Http::get("{$this->fastApiUrl}/booking");

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
                $placeResponse = Http::get("{$this->fastApiUrl}/place/{$booking['place_id']}");
                $place = $placeResponse->successful() ? $placeResponse->json() : null;

                // Get user info for each booking
                $userResponse = Http::get("{$this->fastApiUrl}/user/{$booking['user_id']}");
                $user = $userResponse->successful() ? $userResponse->json() : null;

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
                    'user' => $user,
                    // Parse additional info from user/place
                    'guest_name' => $user['username'] ?? 'Unknown',
                    'guest_email' => $user['email'] ?? null,
                ];
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
        try {
            // Fetch booking details from FastAPI
            $response = Http::get("{$this->fastApiUrl}/booking/{$id}");

            if (!$response->successful()) {
                return redirect()->route('bookings.index')
                    ->with('error', 'Failed to fetch booking details: ' . $response->status());
            }

            $booking = $response->json();

            // Get place info
            $placeResponse = Http::get("{$this->fastApiUrl}/place/{$booking['place_id']}");
            $place = $placeResponse->successful() ? $placeResponse->json() : null;

            // Get user info
            $userResponse = Http::get("{$this->fastApiUrl}/user/{$booking['user_id']}");
            $user = $userResponse->successful() ? $userResponse->json() : null;

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
                'user' => $user,
                // Parse additional info
                'guest_name' => $user['username'] ?? 'Unknown',
                'guest_email' => $user['email'] ?? null,
                'capacity' => isset($place['max_capacity']) ? $place['max_capacity'] : 'Unknown',
                'notes' => null, // FastAPI doesn't store notes
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
        try {
            // Call FastAPI to confirm booking
            $response = Http::patch("{$this->fastApiUrl}/booking/host/confirm/{$id}", [
                'booking_id' => $id,
                'user_id' => 1  // Admin user ID
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
        try {
            // Call FastAPI to cancel booking
            $response = Http::patch("{$this->fastApiUrl}/booking/host/cancel/{$id}", [
                'booking_id' => $id,
                'user_id' => 1  // Admin user ID
            ]);

            if (!$response->successful()) {
                Log::error('Failed to cancel booking in FastAPI', [
                    'booking_id' => $id,
                    'response' => $response->body()
                ]);

                return redirect()->route('bookings.index')
                    ->with('error', 'Failed to cancel booking: ' . ($response->json()['detail'] ?? 'Unknown error'));
            }

            return redirect()->route('bookings.index')
                ->with('success', "Booking #{$id} cancelled successfully!");

        } catch (\Exception $e) {
            Log::error('Booking rejection error', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }
}
