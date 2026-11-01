@extends('layouts.app')

@section('title', $tenant->exists ? 'Ubah Penyewa - huni' : 'Tambah Penyewa - huni')

@section('content')
    <x-modal :title="$tenant->exists ? 'Perbarui profil penyewa' : 'Tambahkan penyewa'" subtitle="Informasi identitas hanya dapat diakses oleh pengguna yang sudah masuk." :cancel-url="route('tenants.index')">
        <form method="POST" enctype="multipart/form-data" action="{{ $tenant->exists ? route('tenants.update', $tenant) : route('tenants.store') }}" class="space-y-5">
            @csrf
            @if ($tenant->exists) @method('PUT') @endif
            <x-input name="name" label="Nama lengkap" :value="$tenant->name" placeholder="Nama sesuai identitas" maxlength="255" :required="true" />
            <div class="grid gap-5 sm:grid-cols-2">
                <x-input name="phone" label="Nomor HP" :value="$tenant->phone" placeholder="08xxxxxxxxxx" maxlength="50" :required="true" />
                <x-input name="id_number" label="Nomor identitas" :value="$tenant->id_number" placeholder="Nomor KTP" maxlength="100" :required="true" />
            </div>
            <x-upload-zone name="id_photo" label="Foto KTP" hint="Format gambar, maksimal 5 MB. File disimpan privat." />
            @if ($tenant->id_photo_path)
                <div class="flex flex-wrap items-center gap-3 rounded-2xl bg-surface-alt p-4">
                    <span class="text-sm font-semibold text-ink">Foto KTP tersimpan</span>
                    <x-button variant="outline" :href="route('tenants.id-photo.preview', $tenant)" target="_blank" rel="noopener">Lihat</x-button>
                    <x-button variant="secondary" :href="route('tenants.id-photo', $tenant)">Unduh</x-button>
                </div>
            @endif
            <div class="grid gap-5 sm:grid-cols-2">
                <x-input name="entry_date" label="Tanggal masuk (opsional)" type="date" :value="$tenant->entry_date?->format('Y-m-d')" />
                <x-input name="exit_date" label="Tanggal keluar (opsional)" type="date" :value="$tenant->exit_date?->format('Y-m-d')" />
            </div>
            <div class="pt-2">
                <x-button type="submit" class="w-full">Simpan penyewa</x-button>
                <a href="{{ route('tenants.index') }}" data-modal-close class="mt-3 block py-2 text-center text-sm font-bold text-muted hover:text-primary">Batal</a>
            </div>
        </form>
    </x-modal>
@endsection
