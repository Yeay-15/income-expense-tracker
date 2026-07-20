<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Income Expense Tracker') }}</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar Landing -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-lg font-bold tracking-tight text-slate-900">ExpenseTracker</span>
        </div>

        @if (Route::has('login'))
        <nav class="flex items-center space-x-3">
            @auth
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition duration-150 ease-in-out shadow-sm">
                Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50 transition duration-150 ease-in-out">
                Log in
            </a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition duration-150 ease-in-out shadow-sm">
                Register
            </a>
            @endif
            @endauth
        </nav>
        @endif
    </header>

    <!-- Hero Section -->
    <main class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="max-w-4xl mx-auto text-center">

            <!-- Badge Info -->
            <div class="inline-flex items-center space-x-2 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full text-xs font-semibold text-blue-700 mb-6">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Sistem Pencatatan Keuangan Pribadi & Multi-Akun</span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-slate-900 mb-6 leading-tight">
                Kelola Keuangan Lebih Mudah,<br />
                <span class="text-blue-600">Pantau Pemasukan & Pengeluaran.</span>
            </h1>

            <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-10">
                Aplikasi web terintegrasi untuk mencatat transaksi harian, mengatur target tabungan, memantau batas anggaran, dan menganalisis laporan finansial secara akurat.
            </p>

            <!-- Feature Highlights Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left mb-12">

                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4 mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Pemasukan Terkontrol</h3>
                    <p class="text-sm text-slate-500">Catat setiap sumber pendapatan dan pantau arus kas masuk secara berkala.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center text-red-600 mb-4 mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Analisis Pengeluaran</h3>
                    <p class="text-sm text-slate-500">Kendalikan budget harian dan dapatkan peringatan otomatis saat mendekati batas.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-600 mb-4 mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-1">Multi Dompet & Transfer</h3>
                    <p class="text-sm text-slate-500">Kelola berbagai jenis akun pembayaran dan transfer saldo dengan transparan.</p>
                </div>

            </div>

            <!-- CTA Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition duration-150 ease-in-out shadow-sm">
                    Masuk ke Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition duration-150 ease-in-out shadow-sm">
                    Mulai Kelola Keuangan
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-white border border-slate-300 rounded-md font-semibold text-sm text-slate-700 uppercase tracking-widest hover:bg-slate-50 transition duration-150 ease-in-out">
                    Buat Akun Baru
                </a>
                @endauth
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 text-center text-xs text-slate-500 border-t border-slate-200 bg-white">
        <p>&copy; {{ date('Y') }} Income & Expense Tracker System. All rights reserved.</p>
    </footer>

</body>

</html>