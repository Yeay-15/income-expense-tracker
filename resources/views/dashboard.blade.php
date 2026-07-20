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
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Net Worth (Semua Akun)</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">
                            Rp {{ number_format($netWorth, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">Selisih bulan ini: Rp {{ number_format($balance, 0, ',', '.') }}</div>
                    </div>
                </div>

                @if($budgetAlerts->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-3">⚠️ Peringatan Budget Bulan Ini</h3>
                        <div class="space-y-2">
                            @foreach($budgetAlerts as $alert)
                            <div class="flex justify-between items-center text-sm">
                                <span>{{ $alert->category->name }}</span>
                                <span class="{{ $alert->percentage >= 100 ? 'text-red-600 font-semibold' : 'text-yellow-600' }}">
                                    {{ $alert->percentage }}% terpakai
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ route('budgets.index') }}" class="text-indigo-600 text-sm hover:underline mt-3 inline-block">Lihat semua budget →</a>
                    </div>
                </div>
                @endif

                <!-- Chart Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="financialChart()" x-init="init()">
                    <div class="p-6 text-gray-900">

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Income vs Expense Chart</h3>
                            <a href="{{ route('dashboard.export.pdf', ['month' => $month, 'year' => $year]) }}" ...>
                                Export to PDF
                            </a>
                        </div>

                        <!-- Tab buttons -->
                        <div class="flex space-x-2 mb-4">
                            <template x-for="tab in ['weekly', 'monthly', 'yearly']" :key="tab">
                                <button
                                    @click="loadData(tab)"
                                    :class="activeTab === tab ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-4 py-1.5 rounded text-sm font-medium capitalize">
                                    <span x-text="tab === 'weekly' ? 'Mingguan' : (tab === 'monthly' ? 'Bulanan' : 'Tahunan')"></span>
                                </button>
                            </template>
                        </div>

                        <div class="relative h-72 w-full">
                            <canvas id="financialChart"></canvas>
                        </div>

                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    function financialChart() {
                        return {
                            activeTab: 'monthly',
                            chartInstance: null,

                            init() {
                                this.loadData('monthly');
                            },

                            async loadData(range) {
                                this.activeTab = range;
                                const response = await fetch(`{{ route('dashboard.chart-data') }}?range=${range}`);
                                const data = await response.json();

                                const ctx = document.getElementById('financialChart').getContext('2d');

                                if (this.chartInstance) {
                                    this.chartInstance.destroy();
                                }

                                this.chartInstance = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: data.labels,
                                        datasets: [{
                                                label: 'Income',
                                                data: data.income,
                                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                            },
                                            {
                                                label: 'Expense',
                                                data: data.expense,
                                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
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
                            },
                        };
                    }
                </script>
</x-app-layout>