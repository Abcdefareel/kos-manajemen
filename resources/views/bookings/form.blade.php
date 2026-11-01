@extends('layouts.app')

@section('title', $booking->exists ? 'Ubah Booking - huni' : 'Buat Booking - huni')

@section('content')
    <x-modal :title="$booking->exists ? 'Perbarui booking' : 'Buat booking baru'" subtitle="Pilih kamar yang tersedia, penyewa, dan rincian pemesanan." :cancel-url="route('bookings.index')">
        @if ($rooms->isEmpty())
            <div class="mb-5 rounded-2xl bg-danger/10 p-4 text-sm leading-6 text-primary-dark">Belum ada kamar kosong. Tambah kamar atau batalkan booking aktif sebelum membuat booking baru.</div>
        @endif
        @if ($tenants->isEmpty())
            <div class="mb-5 rounded-2xl bg-danger/10 p-4 text-sm leading-6 text-primary-dark">Belum ada profil penyewa. <a class="font-bold underline" href="{{ route('tenants.create') }}">Tambah penyewa</a> sebelum membuat booking.</div>
        @endif
        <form method="POST" action="{{ $booking->exists ? route('bookings.update', $booking) : route('bookings.store') }}" class="space-y-5">
            @csrf
            @if ($booking->exists) @method('PUT') @endif
            <x-select name="room_id" label="Kamar tersedia" :required="true">
                <option value="">Pilih kamar kosong</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected((string) old('room_id', $booking->room_id) === (string) $room->id)>{{ $room->room_number }} — {{ $room->type }} — Rp{{ number_format($room->price_per_month, 0, ',', '.') }}/bulan</option>
                @endforeach
            </x-select>
            <x-select name="tenant_id" label="Penyewa penanggung jawab" :required="true">
                <option value="">Pilih penyewa</option>
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" @selected((string) old('tenant_id', $booking->tenant_id) === (string) $tenant->id)>{{ $tenant->name }} ({{ $tenant->phone }})</option>
                @endforeach
            </x-select>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-input name="start_date" label="Tanggal mulai sewa" type="date" :value="$booking->start_date?->format('Y-m-d')" :required="true" />
                <x-input name="duration_months" label="Durasi (bulan)" type="number" :value="$booking->duration_months" min="1" max="120" step="1" placeholder="1" :required="true" />
            </div>
            <x-input name="down_payment" label="Uang muka / DP (rupiah)" type="number" :value="$booking->down_payment" min="0" step="1" placeholder="0" :required="true" />
            <div class="pt-2">
                <x-button type="submit" class="w-full" :disabled="$rooms->isEmpty() || $tenants->isEmpty()">Simpan booking</x-button>
                <a href="{{ route('bookings.index') }}" data-modal-close class="mt-3 block py-2 text-center text-sm font-bold text-muted hover:text-primary">Batal</a>
            </div>
        </form>
    </x-modal>
@endsection
