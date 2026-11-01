@extends('layouts.app')

@section('title', 'Masuk - huni')

@section('content')
    <section class="rounded-3xl bg-surface p-7 shadow-soft sm:p-9">
        <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-periwinkle text-indigo">
            <svg viewBox="0 0 24 24" class="size-7" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5V21H3v-10.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 21v-6h6v6"/></svg>
        </div>
        <h1 class="mt-5 text-center text-2xl font-extrabold tracking-tight text-primary-dark">Selamat datang kembali</h1>
        <p class="mt-2 text-center text-sm text-muted">Masuk untuk mengelola kos dengan lebih nyaman.</p>
        <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-5">
            @csrf
            <x-input name="email" label="Email" type="email" :value="old('email')" autocomplete="username" placeholder="nama@email.com" :required="true" autofocus />
            <x-input name="password" label="Password" type="password" autocomplete="current-password" placeholder="Masukkan password" :required="true" />
            <x-checkbox-pill name="remember" label="Ingat saya" />
            <x-button type="submit" class="w-full">Masuk</x-button>
        </form>
    </section>
@endsection
