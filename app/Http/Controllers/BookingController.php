<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:dipesan,dibatalkan'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $request->validate(['date_to' => ['after_or_equal:date_from']]);
        }
        $search = trim((string) ($filters['search'] ?? ''));

        $bookings = Booking::query()
            ->with(['room', 'tenant'])
            ->when(isset($filters['status']), fn (Builder $query) => $query->where('status', $filters['status']))
            ->when(isset($filters['date_from']), fn (Builder $query) => $query->whereDate('start_date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']), fn (Builder $query) => $query->whereDate('start_date', '<=', $filters['date_to']))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $term = '%'.mb_strtolower($search).'%';
                $query->where(function (Builder $query) use ($term): void {
                    $query->whereHas('tenant', fn (Builder $tenant) => $tenant->whereRaw('LOWER(name) LIKE ?', [$term]))
                        ->orWhereHas('room', fn (Builder $room) => $room->whereRaw('LOWER(room_number) LIKE ?', [$term]));
                });
            })
            ->latest('start_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('bookings.index', [
            'bookings' => $bookings,
            'search' => $search,
            'activeBookingCount' => Booking::where('status', Booking::STATUS_BOOKED)->count(),
            'bookingCount' => Booking::count(),
        ]);
    }

    public function create(): View
    {
        return view('bookings.form', [
            'booking' => new Booking(),
            'rooms' => Room::query()->where('status', Room::STATUS_EMPTY)->orderBy('room_number')->get(),
            'tenants' => Tenant::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data): void {
            $room = Room::query()->whereKey($data['room_id'])->lockForUpdate()->firstOrFail();
            if ($room->status !== Room::STATUS_EMPTY) {
                throw ValidationException::withMessages(['room_id' => 'Kamar sudah tidak kosong dan tidak dapat dibooking.']);
            }

            $room->update(['status' => Room::STATUS_BOOKED]);
            Booking::create($data + ['status' => Booking::STATUS_BOOKED]);
        });

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat.');
    }

    public function edit(Booking $booking): View|RedirectResponse
    {
        if ($booking->status !== Booking::STATUS_BOOKED) {
            return redirect()->route('bookings.index')->with('error', 'Booking yang dibatalkan tidak dapat diubah.');
        }

        return view('bookings.form', [
            'booking' => $booking->load(['room', 'tenant']),
            'rooms' => Room::query()->where(function (Builder $query) use ($booking): void {
                $query->where('status', Room::STATUS_EMPTY)->orWhereKey($booking->room_id);
            })->orderBy('room_number')->get(),
            'tenants' => Tenant::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($booking, $data): void {
            $lockedBooking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== Booking::STATUS_BOOKED) {
                throw ValidationException::withMessages(['booking' => 'Booking yang dibatalkan tidak dapat diubah.']);
            }

            $roomIds = collect([$lockedBooking->room_id, (int) $data['room_id']])->unique()->sort()->values();
            $rooms = Room::query()->whereIn('id', $roomIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $oldRoom = $rooms->get($lockedBooking->room_id);
            $newRoom = $rooms->get((int) $data['room_id']);

            if (! $newRoom || ($newRoom->id !== $oldRoom->id && $newRoom->status !== Room::STATUS_EMPTY)) {
                throw ValidationException::withMessages(['room_id' => 'Kamar yang dipilih sudah tidak kosong.']);
            }

            if ($newRoom->id !== $oldRoom->id) {
                $oldRoom->update(['status' => Room::STATUS_EMPTY]);
                $newRoom->update(['status' => Room::STATUS_BOOKED]);
            }

            $lockedBooking->update($data);
        });

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking): void {
            $lockedBooking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status === Booking::STATUS_CANCELLED) {
                return;
            }

            $room = Room::query()->whereKey($lockedBooking->room_id)->lockForUpdate()->firstOrFail();
            $lockedBooking->update(['status' => Booking::STATUS_CANCELLED]);
            if ($room->status === Room::STATUS_BOOKED) {
                $room->update(['status' => Room::STATUS_EMPTY]);
            }
        });

        return redirect()->route('bookings.index')->with('success', 'Booking dibatalkan dan kamar dikembalikan menjadi kosong.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'start_date' => ['required', 'date'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'down_payment' => ['required', 'integer', 'min:0'],
        ]);
    }
}
