<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        return view('rooms.index', [
            'rooms' => Room::query()->orderBy('room_number')->paginate(15),
            'roomCount' => Room::count(),
            'availableRoomCount' => Room::where('status', Room::STATUS_EMPTY)->count(),
        ]);
    }

    public function create(): View
    {
        return view('rooms.form', ['room' => new Room()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Room::create($this->validatedData($request));

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        return view('rooms.form', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $room->update($this->validatedData($request, $room));

        return redirect()->route('rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->bookings()->exists()) {
            return back()->with('error', 'Kamar tidak dapat dihapus karena memiliki riwayat booking.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Room $room = null): array
    {
        return $request->validate([
            'room_number' => ['required', 'string', 'max:50', Rule::unique('rooms', 'room_number')->ignore($room)],
            'type' => ['required', 'string', 'max:100'],
            'price_per_month' => ['required', 'integer', 'min:0'],
            'facilities' => ['nullable', 'string', 'max:5000'],
        ]);
    }
}
