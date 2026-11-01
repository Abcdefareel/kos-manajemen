@extends('layouts.app')

@section('title', 'huni')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl bg-surface p-8 text-center shadow-soft sm:p-12">
        <div class="mx-auto flex size-16 items-center justify-center rounded-3xl bg-periwinkle text-indigo">
            <svg viewBox="0 0 24 24" class="size-8" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5V21H3v-10.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 21v-6h6v6"/></svg>
        </div>
        <h1 class="mt-6 text-3xl font-extrabold tracking-tight text-primary-dark">Selamat datang di huni</h1>
        <p class="mt-3 text-sm leading-6 text-muted">Kelola kamar, penyewa, dan booking kos dalam satu tempat yang nyaman.</p>
        <x-button :href="route('login')" class="mt-7">Masuk ke aplikasi</x-button>
    </div>
@endsection
