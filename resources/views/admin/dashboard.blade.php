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
                <a href="{{ route('admin.users.index') }}" class="bg-white shadow-sm rounded-lg p-4 text-center hover:bg-gray-50">
                    <div class="text-sm font-medium text-gray-700">👤 User Management</div>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="bg-white shadow-sm rounded-lg p-4 text-center hover:bg-gray-50">
                    <div class="text-sm font-medium text-gray-700">🏷️ Kategori Default</div>
                </a>
                <a href="{{ route('admin.account-types.index') }}" class="bg-white shadow-sm rounded-lg p-4 text-center hover:bg-gray-50">
                    <div class="text-sm font-medium text-gray-700">💳 Jenis Akun</div>
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="bg-white shadow-sm rounded-lg p-4 text-center hover:bg-gray-50">
                    <div class="text-sm font-medium text-gray-700">📢 Pengumuman</div>
                </a>
            </div>

            <!-- Grafik Pendaftaran User -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Pendaftaran User (12 Bulan Terakhir)</h3>
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
