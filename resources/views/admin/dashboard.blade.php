<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pengguna Aktif</div>
                        <div class="mt-2 text-3xl font-bold text-purple-600">{{ $totalActiveUsers }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Transaksi Tercatat</div>
                        <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $totalTransactions }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('admin.users.index') }}" class="bg-white shadow-sm rounded-lg p-4 flex flex-col items-center justify-center hover:bg-indigo-50 transition duration-150 ease-in-out group border border-transparent hover:border-indigo-100">
                    <svg class="w-7 h-7 text-gray-400 group-hover:text-indigo-600 mb-2 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-150">User Management</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="bg-white shadow-sm rounded-lg p-4 flex flex-col items-center justify-center hover:bg-indigo-50 transition duration-150 ease-in-out group border border-transparent hover:border-indigo-100">
                    <svg class="w-7 h-7 text-gray-400 group-hover:text-indigo-600 mb-2 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-150">Kategori Default</span>
                </a>

                <a href="{{ route('admin.account-types.index') }}" class="bg-white shadow-sm rounded-lg p-4 flex flex-col items-center justify-center hover:bg-indigo-50 transition duration-150 ease-in-out group border border-transparent hover:border-indigo-100">
                    <svg class="w-7 h-7 text-gray-400 group-hover:text-indigo-600 mb-2 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-150">Jenis Akun</span>
                </a>

                <a href="{{ route('admin.announcements.index') }}" class="bg-white shadow-sm rounded-lg p-4 flex flex-col items-center justify-center hover:bg-indigo-50 transition duration-150 ease-in-out group border border-transparent hover:border-indigo-100">
                    <svg class="w-7 h-7 text-gray-400 group-hover:text-indigo-600 mb-2 transition-colors duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 transition-colors duration-150">Pengumuman</span>
                </a>
            </div>

            <!-- Grafik Pendaftaran User -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4 text-gray-800">Pendaftaran User (12 Bulan Terakhir)</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="signupChart"
                            data-labels='@json($monthlySignups->keys())'
                            data-values='@json($monthlySignups->values())'>
                        </canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.onload = function() {
            const canvas = document.getElementById('signupChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: JSON.parse(canvas.dataset.labels),
                        datasets: [{
                            label: 'Pendaftaran User Baru',
                            data: JSON.parse(canvas.dataset.values),
                            borderColor: 'rgba(99, 102, 241, 1)',
                            backgroundColor: 'rgba(99, 102, 241, 0.2)',
                            fill: true,
                            tension: 0.3,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                    },
                });
            }
        };
    </script>
</x-app-layout>