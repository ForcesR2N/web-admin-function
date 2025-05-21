<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Event;
use App\Models\eventbackup;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $currentDateTime = Carbon::now();
    
        // Filter bookings to include only future events
        $bookings = Event::where(function ($query) use ($currentDateTime) {
            $query->where('date', '>', $currentDateTime->toDateString())
                  ->orWhere(function ($query) use ($currentDateTime) {
                      $query->where('date', '=', $currentDateTime->toDateString())
                            ->where('finish', '>', $currentDateTime->toTimeString());
                  });
        })->get()->groupBy('nama_rooms');
    
        return view('booking.index', compact('bookings'));
    }
    

    public function create()
    {
        $rooms = Room::all();
        return view('booking.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'acara' => 'required|string|max:255',
            'peserta' => 'required|string|max:255',
            'id_rooms' => 'required|exists:rooms,id',
            'nama_rooms' => 'required|string|max:255',
            'asalbidang' => 'required|string|max:255',
            'date' => 'required|date',
            'start' => 'required|date_format:H:i',
            'finish' => 'required|date_format:H:i|after:start',
        ], [
            'start.required' => 'Kolom mulai wajib diisi.',
            'start.date_format' => 'Format kolom waktu tidak sesuai.',
            'finish.required' => 'Kolom selesai wajib diisi.',
            'finish.date_format' => 'Format kolom waktu tidak sesuai.',
            'finish.after' => 'Waktu selesai harus setelah waktu mulai.',
            'nama.required' => 'Kolom nama wajib diisi.',
            'acara.required' => 'Kolom acara wajib diisi.',
            'id_rooms.required' => 'Kolom ID ruangan wajib diisi.',
            'id_rooms.exists' => 'Ruangan yang dipilih tidak valid.',
            'nama_rooms.required' => 'Kolom nama ruangan wajib diisi.',
            'asalbidang.required' => 'Kolom asal bidang wajib diisi.',
            'asalbidang.in' => 'Asal bidang tidak valid.',
            'date.required' => 'Kolom tanggal wajib diisi.',
            'date.date' => 'Format kolom tanggal tidak valid.',
        ]);

        if (!Event::isRoomAvailable($request->id_rooms, $request->start, $request->finish, $request->date)) {
            return back()->withErrors(['msg' => 'Ruangan tidak tersedia di jadwal yang ditentukan']);
        }

        Event::create($request->all());
        eventbackup::create($request->all());

        return redirect()->route('booking.create')->with('success', 'Ruang berhasil dibooking');
    }

    public function edit(Event $booking)
    {
        $rooms = Room::all();
        return view('booking.edit', compact('booking', 'rooms'));
    }

    public function update(Request $request, Event $booking)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'acara' => 'required|string|max:255',
            'peserta' => 'required|string|max:255',
            'id_rooms' => 'required|exists:rooms,id',
            'nama_rooms' => 'required|string|max:255',
            'asalbidang' => 'required|string|max:255',
            'date' => 'required|date',
            'start' => 'required|date_format:H:i',
            'finish' => 'required|date_format:H:i|after:start',
        ], [
            'start.required' => 'Kolom mulai wajib diisi.',
            'start.date_format' => 'Format kolom waktu tidak sesuai.',
            'finish.required' => 'Kolom selesai wajib diisi.',
            'finish.date_format' => 'Format kolom waktu tidak sesuai.',
            'finish.after' => 'Waktu selesai harus setelah waktu mulai.',
            'nama.required' => 'Kolom nama wajib diisi.',
            'acara.required' => 'Kolom acara wajib diisi.',
            'id_rooms.required' => 'Kolom ID ruangan wajib diisi.',
            'id_rooms.exists' => 'Ruangan yang dipilih tidak valid.',
            'nama_rooms.required' => 'Kolom nama ruangan wajib diisi.',
            'asalbidang.required' => 'Kolom asal bidang wajib diisi.',
            'asalbidang.in' => 'Asal bidang tidak valid.',
            'date.required' => 'Kolom tanggal wajib diisi.',
            'date.date' => 'Format kolom tanggal tidak valid.',
        ]);
    
        if (!Event::isRoomAvailable($request->id_rooms, $request->start, $request->finish, $request->date, $booking->id)) {
            return back()->withErrors(['msg' => 'Ruangan tidak tersedia di jadwal yang ditentukan']);
        }
    
        $booking->update($request->all());
    
        return redirect()->route('booking.index')->with('success', 'Jadwal Berhasil Diperbarui');
    }
    

    public function destroy(Event $booking)
    {
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Jadwal Berhasil Dihapus');
    }

    public function indexroom()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }
}
