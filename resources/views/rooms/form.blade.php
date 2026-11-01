@extends('layouts.app')

@section('title', $room->exists ? 'Ubah Kamar - huni' : 'Tambah Kamar - huni')

@section('content')
    <x-modal :title="$room->exists ? 'Perbarui kamar' : 'Tambahkan kamar'" subtitle="Lengkapi detail kamar. Status ketersediaan dikelola melalui booking." :cancel-url="route('rooms.index')">
        <form method="POST" action="{{ $room->exists ? route('rooms.update', $room) : route('rooms.store') }}" class="space-y-5">
            @csrf
            @if ($room->exists) @method('PUT') @endif
            <x-input name="room_number" label="Nomor kamar" :value="$room->room_number" placeholder="Contoh: A01" maxlength="50" :required="true" />
            <x-input name="type" label="Tipe kamar" :value="$room->type" placeholder="Contoh: Standard" maxlength="100" :required="true" />
            <x-input name="price_per_month" label="Harga per bulan (rupiah)" type="number" :value="$room->price_per_month" placeholder="750000" min="0" step="1" :required="true" />
            <div>
                <label for="facilities" class="mb-2 block text-xs font-bold uppercase tracking-[0.12em] text-muted">Fasilitas</label>
                <textarea id="facilities" name="facilities" rows="3" placeholder="Tuliskan fasilitas kamar" class="w-full rounded-3xl bg-surface-alt px-5 py-4 text-sm text-ink placeholder:text-muted/70 focus:ring-4 focus:ring-pink/50">{{ old('facilities', $room->facilities) }}</textarea>
                @error('facilities')<p class="mt-2 text-xs font-semibold text-primary" role="alert">{{ $message }}</p>@enderror
            </div>
            @if ($room->exists)<p class="flex items-center gap-2 text-sm text-muted">Status saat ini: <x-badge :status="$room->status" :label="ucfirst($room->status)" /></p>@endif
            <div class="pt-2">
                <x-button type="submit" class="w-full">Simpan kamar</x-button>
                <a href="{{ route('rooms.index') }}" data-modal-close class="mt-3 block py-2 text-center text-sm font-bold text-muted hover:text-primary">Batal</a>
            </div>
        </form>
    </x-modal>
@endsection
