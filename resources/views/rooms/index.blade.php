@extends('layouts.app')

@section('title', 'Kamar - huni')

@section('content')
    <x-page-header title="Kamar kos" subtitle="Kelola informasi dan ketersediaan kamar di satu tempat. Status kamar berubah melalui proses booking.">
        <x-slot:actions>
            <x-button :href="route('rooms.create')">
                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                Tambah kamar
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <x-stat-card label="Semua kamar" :value="$roomCount" detail="Jumlah kamar terdaftar" icon='<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 20V8l9-5 9 5v12M3 14h18M6 20v-3m12 3v-3"/></svg>' />
        <x-stat-card label="Kamar tersedia" :value="$availableRoomCount" detail="Siap menerima booking" variant="primary" icon='<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6"/></svg>' />
    </div>

    <x-table-card :paginator="$rooms" label="kamar">
        <table class="w-full min-w-[760px] text-left">
            <thead class="bg-surface-alt text-xs font-bold uppercase tracking-[0.14em] text-muted">
                <tr><th class="px-5 py-4">Nomor</th><th class="px-5 py-4">Tipe</th><th class="px-5 py-4">Harga/bulan</th><th class="px-5 py-4">Fasilitas</th><th class="px-5 py-4">Status</th><th class="px-5 py-4 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-neutral/60 text-sm">
            @forelse ($rooms as $room)
                <tr class="transition hover:bg-page/70">
                    <td class="px-5 py-4 font-bold text-ink">{{ $room->room_number }}</td>
                    <td class="px-5 py-4 text-muted">{{ $room->type }}</td>
                    <td class="px-5 py-4 font-extrabold text-indigo">Rp{{ number_format($room->price_per_month, 0, ',', '.') }}</td>
                    <td class="max-w-xs px-5 py-4 text-muted">{{ $room->facilities ?: '—' }}</td>
                    <td class="px-5 py-4"><x-badge :status="$room->status" :label="ucfirst($room->status)" /></td>
                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-2">
                            <x-button variant="icon" :href="route('rooms.edit', $room)" ariaLabel="Ubah kamar {{ $room->room_number }}">
                                <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m14 5 5 5M4 20l4.5-1 10-10a2.1 2.1 0 0 0-3-3l-10 10L4 20Z"/></svg>
                            </x-button>
                            <form method="POST" action="{{ route('rooms.destroy', $room) }}" data-confirm-delete="kamar {{ $room->room_number }}">
                                @csrf @method('DELETE')
                                <x-button type="submit" variant="icon" ariaLabel="Hapus kamar {{ $room->room_number }}">
                                    <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3"/></svg>
                                </x-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-5"><x-empty-state title="Belum ada kamar" description="Tambahkan data kamar agar dapat mulai menerima booking." /></td></tr>
            @endforelse
            </tbody>
        </table>
    </x-table-card>
@endsection
