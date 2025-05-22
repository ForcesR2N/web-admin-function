<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\MobileBooking;

class ApiBookingController extends Controller
{
    /**
     * Display a listing of mobile bookings.
     */
    public function index()
    {
        $bookings = MobileBooking::orderBy('created_at', 'desc')->get();
        return view('mobile-bookings.index', compact('bookings'));
    }

    /**
     * Approve a booking request.
     */
    public function approve(MobileBooking $booking)
    {
        $booking->status = 'approved';
        $booking->processed_at = Carbon::now();
        $booking->save();

        return redirect()->route('mobile-bookings.index')
            ->with('success', 'Booking request approved successfully');
    }

    /**
     * Reject a booking request.
     */
    public function reject(MobileBooking $booking)
    {
        $booking->status = 'rejected';
        $booking->processed_at = Carbon::now();
        $booking->save();

        return redirect()->route('mobile-bookings.index')
            ->with('success', 'Booking request rejected successfully');
    }

    /**
     * API endpoint to create a booking (to be called from mobile app)
     */
    public function apiCreate(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'required|integer',
            'user_id' => 'required|integer',
            'venue_name' => 'required|string',
            'user_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer',
            'contact_info' => 'required|string',
        ]);

        $booking = new MobileBooking();
        $booking->venue_id = $validated['venue_id'];
        $booking->user_id = $validated['user_id'];
        $booking->venue_name = $validated['venue_name'];
        $booking->user_name = $validated['user_name'];
        $booking->start_date = $validated['start_date'];
        $booking->end_date = $validated['end_date'];
        $booking->capacity = $validated['capacity'];
        $booking->contact_info = $validated['contact_info'];
        $booking->status = 'pending';
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking request submitted successfully',
            'booking_id' => $booking->id
        ]);
    }

    /**
     * API endpoint for checking booking status
     */
    public function apiCheckStatus(Request $request, $id)
    {
        $booking = MobileBooking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $booking->status,
            'processed_at' => $booking->processed_at
        ]);
    }
}
