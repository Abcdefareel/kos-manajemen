@extends('layouts.app')

@section('title', 'Booking - huni')

@section('content')
    <x-page-header title="Booking kamar" subtitle="Atur pemesanan kamar dan uang muka penyewa dengan mudah.">
        <x-slot:actions>
            <x-button :href="route('bookings.create')">
                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                Buat booking
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <x-stat-card label="Semua booking" :value="$bookingCount" detail="Termasuk riwayat pembatalan" icon='<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="3"/><path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/></svg>' />
        <x-stat-card label="Booking aktif" :value="$activeBookingCount" detail="Kamar berstatus dipesan" variant="primary" icon='<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>' />
    </div>

    <x-filter-bar class="mb-6">
        <form method="GET" action="{{ route('bookings.index') }}" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <x-input name="search" label="Nama penyewa / nomor kamar" :value="$search" placeholder="Kata kunci" />
            <x-select name="status" label="Status">
                <option value="">Semua status</option>
                <option value="dipesan" @selected(request('status') === 'dipesan')>Dipesan</option>
                <option value="dibatalkan" @selected(request('status') === 'dibatalkan')>Dibatalkan</option>
            </x-select>
            <x-input name="date_from" label="Mulai dari" type="date" :value="request('date_from')" />
            <x-input name="date_to" label="Sampai tanggal" type="date" :value="request('date_to')" />
            <div class="flex items-end gap-2">
                <x-button type="submit">Cari</x-button>
                <x-button variant="outline" :href="route('bookings.index')">Reset</x-button>
            </div>
        </form>
    </x-filter-bar>

    <x-table-card :paginator="$bookings" label="booking">
        <table class="w-full min-w-[820px] text-left">
            <thead class="bg-surface-alt text-xs font-bold uppercase tracking-[0.14em] text-muted">
                <tr><th class="px-5 py-4">Tanggal mulai</th><th class="px-5 py-4">Kamar</th><th class="px-5 py-4">Penyewa</th><th class="px-5 py-4">Durasi</th><th class="px-5 py-4">Uang muka</th><th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-neutral/60 text-sm">
            @forelse ($bookings as $booking)
                <tr class="transition hover:bg-page/70">
                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-ink">{{ $booking->start_date->format('d-m-Y') }}</td>
                    <td class="px-5 py-4 font-bold text-ink">{{ $booking->room->room_number }}</td>
                    <td class="px-5 py-4 text-ink">{{ $booking->tenant->name }}</td>
                    <td class="px-5 py-4 text-muted">{{ $booking->duration_months }} bulan</td>
                    <td class="whitespace-nowrap px-5 py-4 font-extrabold text-indigo">Rp{{ number_format($booking->down_payment, 0, ',', '.') }}</td>
                    <td class="px-5 py-4"><x-badge :status="$booking->status" :label="ucfirst($booking->status)" /></td>
                    <td class="px-5 py-4">
                        @if ($booking->status === 'dipesan')
                            <div class="flex justify-end gap-2">
                                <x-button variant="icon" :href="route('bookings.edit', $booking)" ariaLabel="Ubah booking kamar {{ $booking->room->room_number }}">
                                    <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m14 5 5 5M4 20l4.5-1 10-10a2.1 2.1 0 0 0-3-3l-10 10L4 20Z"/></svg>
                                </x-button>
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" data-confirm-delete="booking kamar {{ $booking->room->room_number }} untuk {{ $booking->tenant->name }}" data-confirm-action="Batalkan" data-confirm-verb="membatalkan">
                                    @csrf @method('PATCH')
                                    <x-button type="submit" variant="icon" ariaLabel="Batalkan booking kamar {{ $booking->room->room_number }}">
                                        <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18"/></svg>
                                    </x-button>
                                </form>
                            </div>
                        @else
                            <div class="text-right text-xs text-muted">Tidak ada aksi</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="p-5"><x-empty-state title="Belum ada booking" description="Booking yang cocok dengan pencarian akan muncul di sini."><x-button :href="route('bookings.create')">Buat booking</x-button></x-empty-state></td></tr>
            @endforelse
            </tbody>
        </table>
    </x-table-card>
@endsection
