<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Income Expense Tracker') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    {{-- Header --}}
    <header class="bg-white shadow-sm border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">

            {{-- Bagian Logo yang sudah disamakan dengan Navigasi --}}
            <div class="flex items-center gap-3">
                <x-application-logo class="block h-10 w-auto fill-current text-slate-800" />
                <span class="font-bold text-xl text-slate-800">{{ config('app.name', 'Income Expense Tracker') }}</span>
            </div>

            @if (Route::has('login'))
            <nav class="flex items-center gap-4 text-sm font-medium">
                @auth
                <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-primary-600 transition">
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="text-slate-600 hover:text-primary-600 transition">
                    Log in
                </a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    Register
                </a>
                @endif
                @endauth
            </nav>
            @endif
        </div>
    </header>

    {{-- Body --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Hero Section --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-16 sm:py-24 text-center">
                    <h2 class="font-bold text-3xl sm:text-5xl text-slate-800 leading-tight tracking-tight">
                        Kelola Keuangan Anda <br class="hidden sm:block"> Dengan Lebih Rapi
                    </h2>
                    <p class="mt-6 text-lg sm:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                        Catat pemasukan dan pengeluaran, atur kategori, dan pantau anggaran
                        dalam satu tempat yang sederhana.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                        <a href="{{ url('/dashboard') }}"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-primary-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            Buka Dashboard
                        </a>
                        @else
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-primary-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            Mulai Sekarang
                        </a>
                        @endif
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-white border-2 border-slate-200 rounded-md font-semibold text-sm text-slate-700 uppercase tracking-widest hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            Log in
                        </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Feature cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-primary-600">
                    <div class="p-6">
                        <svg class="w-6 h-6 text-primary-600 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18M3 12h18M3 18h12" />
                        </svg>
                        <div class="text-base font-bold text-slate-800 uppercase tracking-wider mb-2">Catat Transaksi</div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Tambahkan pemasukan dan pengeluaran harian Anda dengan antarmuka yang cepat dan mudah.
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <svg class="w-6 h-6 text-indigo-600 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.17H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .66 1.41l9.59 9.59a2 2 0 0 0 2.83 0l4.51-4.51a2 2 0 0 0 0-2.83Z" />
                            <circle cx="7.5" cy="7.5" r="1" />
                        </svg>
                        <div class="text-base font-bold text-slate-800 uppercase tracking-wider mb-2">Kategori Dinamis</div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Kelompokkan transaksi sesuai kategori masing-masing untuk laporan yang lebih akurat.
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-success-600">
                    <div class="p-6">
                        <svg class="w-6 h-6 text-success-600 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18" />
                            <path d="M18.4 8.6 13 14l-3-3-4.4 4.4" />
                        </svg>
                        <div class="text-base font-bold text-slate-800 uppercase tracking-wider mb-2">Pantau Anggaran</div>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Pantau batas pengeluaran per kategori setiap bulan agar tidak melebihi rencana.
                        </p>
                    </div>
                </div>

            </div>

            <p class="text-center text-sm font-medium text-slate-400 mt-12 pb-4">
                &copy; {{ date('Y') }} {{ config('app.name', 'Income Expense Tracker') }}
            </p>

        </div>
    </div>
</body>

</html>