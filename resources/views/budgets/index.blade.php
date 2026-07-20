<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Budget Bulanan') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <form method="GET" class="flex space-x-2">
                        <select name="month" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                            @endforeach
                        </select>
                        <select name="year" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                            @foreach(range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>

                    <a href="{{ route('budgets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + Tambah Budget
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($budgets as $budget)
                    @php
                    $barColor = $budget->percentage >= 100 ? 'bg-red-600' : ($budget->percentage >= 80 ? 'bg-yellow-500' : 'bg-green-500');
                    @endphp
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-medium">{{ $budget->category->name }}</span>
                            <div class="flex items-center space-x-3 text-sm">
                                <span>Rp {{ number_format($budget->spent, 0, ',', '.') }} / Rp {{ number_format($budget->amount, 0, ',', '.') }}</span>
                                <a href="{{ route('budgets.edit', $budget->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST" onsubmit="return confirm('Hapus budget ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="{{ $barColor }} h-2.5 rounded-full" style="width: {{ max(0, min(100, $budget->percentage)) }}%;"></div>
                        </div>
                        @if($budget->percentage >= 100)
                        <p class="text-xs text-red-600 mt-1">Anggaran terlampaui!</p>
                        @elseif($budget->percentage >= 80)
                        <p class="text-xs text-yellow-600 mt-1">Mendekati batas anggaran.</p>
                        @endif
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Belum ada budget untuk periode ini.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
