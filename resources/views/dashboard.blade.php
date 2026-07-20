<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-end mb-4">
                <form method="GET" class="flex space-x-2">
                    <select name="month" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                        @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                        @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Income Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-success-600">
                    <div class="p-6">
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Income</div>
                        <div class="mt-2 text-3xl font-bold text-success-600">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Total Expense Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-danger-600">
                    <div class="p-6">
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Expense</div>
                        <div class="mt-2 text-3xl font-bold text-danger-600">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Total Savings Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Tabungan</div>
                        <div class="mt-2 text-3xl font-bold text-indigo-600">
                            Rp {{ number_format($totalSavings, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Balance Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-primary-600">
                    <div class="p-6">
                        <div class="text-sm font-medium text-slate-500 uppercase tracking-wider">Net Worth (Semua Akun)</div>
                        <div class="mt-2 text-3xl font-bold text-primary-600">
                            Rp {{ number_format($netWorth, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-slate-400 mt-1">Selisih bulan ini: Rp {{ number_format($balance, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            @if($budgetAlerts->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6 border-l-4 border-warning-400">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-3 text-slate-800">⚠️ Peringatan Budget Bulan Ini</h3>
                    <div class="space-y-2">
                        @foreach($budgetAlerts as $alert)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-700">{{ $alert->category->name }}</span>
                            <span class="{{ $alert->percentage >= 100 ? 'text-danger-600 font-semibold' : 'text-warning-500 font-semibold' }}">
                                {{ $alert->percentage }}% terpakai
                            </span>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('budgets.index') }}" class="text-primary-600 text-sm hover:underline mt-3 inline-block">Lihat semua budget →</a>
                </div>
            </div>
            @endif

            <!-- Chart Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6" x-data="financialChart()" x-init="init()">
                <div class="p-6 text-slate-900">

                    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
                        <h3 class="text-lg font-medium">Income vs Expense Chart</h3>
                        <a href="{{ route('dashboard.export.pdf', ['month' => $month, 'year' => $year]) }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Export to PDF
                        </a>
                    </div>

                    <!-- Tab buttons -->
                    <div class="flex space-x-2 mb-4">
                        <template x-for="tab in ['weekly', 'monthly', 'yearly']" :key="tab">
                            <button
                                type="button"
                                @click="loadData(tab)"
                                :class="activeTab === tab ? 'bg-primary-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="px-4 py-1.5 rounded text-sm font-medium capitalize transition ease-in-out duration-150">
                                <span x-text="tab === 'weekly' ? 'Mingguan' : (tab === 'monthly' ? 'Bulanan' : 'Tahunan')"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Chart Container (Diperbaiki) -->
                    <div class="relative w-full" style="height: 400px;">
                        <canvas x-ref="chartCanvas"></canvas>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function financialChart() {
            // KUNCI PERBAIKAN: chartInstance diletakkan di luar return agar tidak di-proxy oleh Alpine.js
            let chartInstance = null;

            return {
                activeTab: 'monthly',

                init() {
                    this.loadData('monthly');
                },

                async loadData(range) {
                    this.activeTab = range;

                    try {
                        const response = await fetch(`{{ route('dashboard.chart-data') }}?range=${range}`);
                        const data = await response.json();

                        // Menggunakan $refs untuk mengambil elemen canvas lebih aman di Alpine.js
                        const ctx = this.$refs.chartCanvas.getContext('2d');

                        if (chartInstance) {
                            chartInstance.destroy();
                        }

                        chartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                        label: 'Income',
                                        data: data.income,
                                        backgroundColor: 'rgba(25, 135, 84, 0.85)',
                                    },
                                    {
                                        label: 'Expense',
                                        data: data.expense,
                                        backgroundColor: 'rgba(220, 53, 69, 0.85)',
                                    },
                                ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                },
                            },
                        });
                    } catch (error) {
                        console.error("Gagal memuat data chart:", error);
                    }
                },
            };
        }
    </script>
</x-app-layout>