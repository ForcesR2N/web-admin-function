<?php
// app/Http/Controllers/ApiBookingController.php
// SOLUSI: Laravel sebagai proxy ke backend FastAPI

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;

class ApiBookingController extends Controller
{
    private string $backendUrl;

    public function __construct()
    {
        // Backend FastAPI URL
        $this->backendUrl = env('BACKEND_API_URL', 'http://127.0.0.1:8001/api');
    }

    /**
     * PROXY: Create booking via backend FastAPI
     * Mobile App -> Laravel -> FastAPI Backend
     */
    public function apiCreate(Request $request)
    {
        try {
            Log::info('Mobile booking request received', $request->all());

            // Validate input dari mobile app
            $validated = $request->validate([
                'venue_id' => 'required|integer',
                'user_id' => 'nullable|integer',
                'venue_name' => 'required|string',
                'user_name' => 'required|string',
                'user_email' => 'required|email',
                'user_phone' => 'nullable|string',
                'start_date' => 'required|string', // ISO datetime string
                'end_date' => 'required|string',   // ISO datetime string
                'capacity' => 'required|integer',
                'special_requests' => 'nullable|string',
                'total_price' => 'nullable|numeric',
            ]);

            // Transform data untuk backend FastAPI format
            $backendData = $this->transformToBackendFormat($validated);

            // Create dummy user jika diperlukan (untuk testing)
            $userId = $validated['user_id'] ?? $this->getOrCreateDummyUser();

            // Send request ke backend FastAPI
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post("{$this->backendUrl}/booking", $backendData);

            Log::info('Backend response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $backendBooking = $response->json();

                // Optional: Simpan juga di Laravel database untuk tracking
                $localBooking = $this->saveLocalBooking($backendBooking, $validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Booking created successfully',
                    'booking' => $backendBooking,
                    'local_id' => $localBooking->id ?? null
                ], 201);
            }

            // Handle backend errors
            $errorData = $response->json();
            Log::error('Backend booking failed', [
                'status' => $response->status(),
                'error' => $errorData
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Backend booking failed',
                'error' => $errorData['detail'] ?? 'Unknown error'
            ], $response->status());

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Mobile booking error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transform mobile app data ke format backend FastAPI
     */
    private function transformToBackendFormat(array $mobileData): array
    {
        // Parse datetime strings
        $startDate = new \DateTime($mobileData['start_date']);
        $endDate = new \DateTime($mobileData['end_date']);

        return [
            'place_id' => $mobileData['venue_id'],
            'user_id' => $mobileData['user_id'] ?? 1, // Default user untuk testing
            'start_time' => $startDate->format('H:i:s'),
            'end_time' => $endDate->format('H:i:s'),
            'date' => $startDate->format('Y-m-d'),
            'is_confirmed' => false,
        ];
    }

    /**
     * Get or create dummy user untuk testing
     */
    private function getOrCreateDummyUser(): int
    {
        // Return default user ID
        return 1;
    }

    /**
     * Save booking di Laravel database untuk tracking (optional)
     */
    private function saveLocalBooking(array $backendBooking, array $mobileData): ?Booking
    {
        try {
            return Booking::create([
                'place_id' => $backendBooking['place_id'],
                'user_id' => $backendBooking['user_id'],
                'start_time' => $backendBooking['start_time'],
                'end_time' => $backendBooking['end_time'],
                'date' => $backendBooking['date'],
                'is_confirmed' => $backendBooking['is_confirmed'],
                // Tambahan data dari mobile
                'mobile_data' => json_encode([
                    'venue_name' => $mobileData['venue_name'],
                    'user_name' => $mobileData['user_name'],
                    'user_email' => $mobileData['user_email'],
                    'capacity' => $mobileData['capacity'],
                    'special_requests' => $mobileData['special_requests'] ?? null,
                ])
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to save local booking', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * PROXY: Get booking status from backend
     */
    public function apiCheckStatus($id)
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->backendUrl}/booking/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Status check error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check status'
            ], 500);
        }
    }

    /**
     * List all bookings (untuk admin)
     */
    public function index()
    {
        try {
            // Get bookings dari Laravel database
            $bookings = Booking::with(['place', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $stats = [
                'total' => $bookings->count(),
                'pending' => $bookings->where('is_confirmed', false)->count(),
                'confirmed' => $bookings->where('is_confirmed', true)->count(),
            ];

            return view('bookings.index', compact('bookings', 'stats'));

        } catch (\Exception $e) {
            Log::error('Bookings index error', ['error' => $e->getMessage()]);

            return view('bookings.index', [
                'bookings' => collect([]),
                'stats' => ['total' => 0, 'pending' => 0, 'confirmed' => 0],
                'error' => 'Failed to load bookings: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Approve booking
     */
    public function approve($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->update(['is_confirmed' => true]);

            // Optional: Sync dengan backend FastAPI
            $this->syncWithBackend($booking);

            return redirect()->route('bookings.index')
                ->with('success', "Booking #{$booking->id} confirmed successfully!");

        } catch (\Exception $e) {
            Log::error('Booking approval error', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to confirm booking');
        }
    }

    /**
     * Reject booking
     */
    public function reject($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Optional: Notify backend sebelum delete
            $this->notifyBackendDeletion($booking);

            $booking->delete();

            return redirect()->route('bookings.index')
                ->with('success', "Booking #{$booking->id} cancelled successfully!");

        } catch (\Exception $e) {
            Log::error('Booking rejection error', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to cancel booking');
        }
    }

    /**
     * Sync Laravel booking dengan backend (optional)
     */
    private function syncWithBackend(Booking $booking): void
    {
        try {
            // Implementasi sync jika diperlukan
            // Http::patch("{$this->backendUrl}/booking/{$booking->id}", [...]);
        } catch (\Exception $e) {
            Log::warning('Backend sync failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notify backend tentang deletion (optional)
     */
    private function notifyBackendDeletion(Booking $booking): void
    {
        try {
            // Implementasi notification jika diperlukan
            // Http::delete("{$this->backendUrl}/booking/{$booking->id}");
        } catch (\Exception $e) {
            Log::warning('Backend delete notification failed', ['error' => $e->getMessage()]);
        }
    }
}
