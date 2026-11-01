<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'huni')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @auth
        <div class="min-h-screen md:flex">
            <aside class="fixed inset-x-3 bottom-3 z-30 flex h-16 items-center justify-around rounded-[40px] bg-primary-dark p-2 text-white shadow-soft md:inset-y-4 md:left-4 md:right-auto md:h-auto md:w-20 md:flex-col md:justify-start md:gap-4 md:py-6">
                <a href="{{ route('bookings.index') }}" aria-label="huni - Booking" title="huni" class="mb-1 hidden size-11 items-center justify-center rounded-2xl bg-white/10 md:flex">
                    <svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5V21H3v-10.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 21v-6h6v6"/></svg>
                </a>
                <nav aria-label="Menu utama" class="flex w-full items-center justify-around md:flex-1 md:flex-col md:justify-start md:gap-3">
                    <a href="{{ route('bookings.index') }}" aria-label="Booking" title="Booking" class="{{ request()->routeIs('bookings.*') ? 'bg-white text-primary-dark' : 'text-white/80 hover:bg-white/10' }} flex size-11 items-center justify-center rounded-full transition">
                        <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="3"/><path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 15 2.5 2.5L16 12"/></svg>
                    </a>
                    <a href="{{ route('rooms.index') }}" aria-label="Kamar" title="Kamar" class="{{ request()->routeIs('rooms.*') ? 'bg-white text-primary-dark' : 'text-white/80 hover:bg-white/10' }} flex size-11 items-center justify-center rounded-full transition">
                        <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 20V8l9-5 9 5v12M3 14h18M6 20v-3m12 3v-3"/><path stroke-linecap="round" d="M7 11h3v3H7z"/></svg>
                    </a>
                    <a href="{{ route('tenants.index') }}" aria-label="Penyewa" title="Penyewa" class="{{ request()->routeIs('tenants.*') ? 'bg-white text-primary-dark' : 'text-white/80 hover:bg-white/10' }} flex size-11 items-center justify-center rounded-full transition">
                        <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="9" cy="8" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 21a6.5 6.5 0 0 1 13 0M16 4.5a4 4 0 0 1 0 7.5m2 3a5.5 5.5 0 0 1 3.5 5"/></svg>
                    </a>
                </nav>
            </aside>

            <div class="min-w-0 flex-1 pb-24 md:ml-28 md:pb-0">
                <header class="mx-auto flex max-w-[1500px] flex-wrap items-center gap-4 px-5 py-5 sm:px-8 lg:px-12">
                    <a href="{{ route('bookings.index') }}" class="mr-auto text-lg font-extrabold tracking-tight text-primary-dark sm:mr-0">huni</a>
                    <form method="GET" action="{{ route('bookings.index') }}" role="search" class="order-3 w-full sm:order-none sm:mx-auto sm:max-w-md sm:flex-1">
                        <label class="sr-only" for="global-search">Cari booking, penghuni, atau kamar</label>
                        <div class="relative">
                            <svg viewBox="0 0 24 24" class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-muted" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path stroke-linecap="round" d="m16 16 4.5 4.5"/></svg>
                            <input id="global-search" name="search" value="{{ request()->routeIs('bookings.*') ? request('search') : '' }}" placeholder="Cari booking, penghuni, kamar..." class="w-full rounded-full bg-surface-alt py-3 pl-12 pr-5 text-sm text-ink placeholder:text-muted/70 focus:ring-4 focus:ring-pink/50">
                        </div>
                    </form>
                    <div class="flex items-center gap-3">
                        <span class="hidden text-right sm:block"><span class="block text-sm font-bold text-ink">{{ auth()->user()->name }}</span><span class="block text-xs capitalize text-muted">{{ auth()->user()->role }}</span></span>
                        <span aria-label="Profil {{ auth()->user()->name }}" class="flex size-11 items-center justify-center rounded-full bg-periwinkle font-extrabold text-indigo">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full bg-surface-alt px-4 py-2.5 text-xs font-bold text-muted transition hover:bg-pink/20 hover:text-primary-dark">Keluar</button>
                        </form>
                    </div>
                </header>
                <main class="mx-auto max-w-[1500px] px-5 pb-12 sm:px-8 lg:px-12">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="flex min-h-screen items-center justify-center bg-page px-4 py-10">
            <div class="w-full max-w-md">
                <a href="{{ route('login') }}" class="mb-8 block text-center text-2xl font-extrabold tracking-tight text-primary-dark">huni</a>
                @yield('content')
            </div>
        </main>
    @endauth

    <div class="fixed right-4 top-4 z-[60] flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3">
        @if (session('success'))<x-toast message="{{ session('success') }}" />@endif
        @if (session('error'))<x-toast type="error" message="{{ session('error') }}" />@endif
        @if ($errors->any())
            <x-toast type="error" message="Periksa kembali data yang dimasukkan. {{ $errors->first() }}" />
        @endif
    </div>
    <x-confirm-delete />
</body>
</html>
