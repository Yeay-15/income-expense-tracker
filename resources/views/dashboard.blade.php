<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Income Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Income</div>
                        <div class="mt-2 text-3xl font-bold text-green-600">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Total Expense Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Expense</div>
                        <div class="mt-2 text-3xl font-bold text-red-600">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Balance Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Current Balance</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Ubah bagian header ini untuk menambahkan tombol export -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Income vs Expense Chart</h3>
                        <a href="{{ route('dashboard.export.pdf') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                            Export to PDF
                        </a>
                    </div>

                    <!-- Chart Canvas -->
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="financialChart" data-income="{{ $totalIncome }}" data-expense="{{ $totalExpense }}"></canvas>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Script via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.onload = function() {
            const canvas = document.getElementById('financialChart');

            // Check if canvas exists to prevent errors
            if (canvas) {
                const ctx = canvas.getContext('2d');

                // Read data directly from HTML data-* attributes (Formatter-safe)
                const incomeData = canvas.dataset.income;
                const expenseData = canvas.dataset.expense;

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Income', 'Expense'],
                        datasets: [{
                            label: 'Total Amount (Rp)',
                            data: [incomeData, expenseData],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.8)', // Green
                                'rgba(239, 68, 68, 0.8)' // Red
                            ],
                            borderColor: [
                                'rgba(34, 197, 94, 1)',
                                'rgba(239, 68, 68, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }
        };
    </script>
</x-app-layout>