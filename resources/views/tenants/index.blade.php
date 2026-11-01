@extends('layouts.app')

@section('title', 'Penyewa - huni')

@section('content')
    <x-page-header title="Data penyewa" subtitle="Simpan informasi penghuni dan lihat dokumen identitas dengan aman.">
        <x-slot:actions>
            <x-button :href="route('tenants.create')">
                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                Tambah penyewa
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <x-stat-card label="Penyewa terdaftar" :value="$tenantCount" detail="Semua profil penyewa" icon='<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 21a6.5 6.5 0 0 1 13 0M16 4.5a4 4 0 0 1 0 7.5m2 3a5.5 5.5 0 0 1 3.5 5"/></svg>' />
        <x-stat-card label="Hasil ditampilkan" :value="$tenants->total()" detail="Sesuai pencarian saat ini" variant="primary" icon='<svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="10.8" cy="10.8" r="6.8"/><path stroke-linecap="round" d="m16 16 4.5 4.5"/></svg>' />
    </div>

    <x-filter-bar class="mb-6">
        <form method="GET" action="{{ route('tenants.index') }}" class="flex flex-col gap-3 sm:flex-row">
            <x-input name="search" label="Cari nama penyewa" :value="$search" placeholder="Ketik nama penyewa" class="flex-1" />
            <div class="flex items-end gap-2">
                <x-button type="submit">Cari</x-button>
                @if ($search !== '')<x-button variant="outline" :href="route('tenants.index')">Reset</x-button>@endif
            </div>
        </form>
    </x-filter-bar>

    @if ($tenants->count())
        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($tenants as $tenant)
                <x-data-card :title="$tenant->name" :subtitle="$tenant->phone">
                    <x-slot:details>
                        <dl class="grid grid-cols-2 gap-3 text-sm">
                            <div><dt class="section-label">Nomor identitas</dt><dd class="mt-1 break-all font-semibold text-ink">{{ $tenant->id_number }}</dd></div>
                            <div><dt class="section-label">Masuk / keluar</dt><dd class="mt-1 text-ink">{{ $tenant->entry_date?->format('d-m-Y') ?? '—' }} / {{ $tenant->exit_date?->format('d-m-Y') ?? '—' }}</dd></div>
                        </dl>
                        @if ($tenant->id_photo_path)
                            <div class="mt-4 flex flex-wrap gap-2">
                                <x-button variant="outline" :href="route('tenants.id-photo.preview', $tenant)" target="_blank" rel="noopener">
                                    <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Lihat KTP
                                </x-button>
                                <x-button variant="secondary" :href="route('tenants.id-photo', $tenant)">Unduh KTP</x-button>
                            </div>
                        @else
                            <p class="mt-4 text-sm text-muted">Foto KTP belum ditambahkan.</p>
                        @endif
                    </x-slot:details>
                    <x-slot:actions>
                        <x-button variant="outline" :href="route('tenants.edit', $tenant)">Ubah data</x-button>
                        <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" data-confirm-delete="penyewa {{ $tenant->name }}">
                            @csrf @method('DELETE')
                            <x-button type="submit" variant="icon" ariaLabel="Hapus data penyewa {{ $tenant->name }}">
                                <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3"/></svg>
                            </x-button>
                        </form>
                    </x-slot:actions>
                </x-data-card>
            @endforeach
        </div>
        <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-muted">Menampilkan {{ $tenants->firstItem() }}–{{ $tenants->lastItem() }} dari {{ $tenants->total() }} penyewa</p>
            <nav aria-label="Pagination" class="flex items-center gap-2">
                @if ($tenants->previousPageUrl())<a href="{{ $tenants->previousPageUrl() }}" class="rounded-full bg-neutral px-4 py-2 text-sm font-bold text-ink">Sebelum</a>@else<span class="rounded-full bg-neutral/60 px-4 py-2 text-sm font-bold text-muted/60">Sebelum</span>@endif
                <span class="px-2 text-xs font-bold text-muted">{{ $tenants->currentPage() }} / {{ $tenants->lastPage() }}</span>
                @if ($tenants->nextPageUrl())<a href="{{ $tenants->nextPageUrl() }}" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-white shadow-primary">Lanjut</a>@else<span class="rounded-full bg-primary/40 px-4 py-2 text-sm font-bold text-white/80">Lanjut</span>@endif
            </nav>
        </div>
    @else
        <x-empty-state title="Belum ada data penyewa" description="Tambahkan profil penyewa atau ubah kata pencarian.">
            <x-button :href="route('tenants.create')">Tambah penyewa</x-button>
        </x-empty-state>
    @endif
@endsection
