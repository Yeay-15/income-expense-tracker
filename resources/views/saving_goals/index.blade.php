<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Target Tabungan') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium">Daftar Target</h3>
                <a href="{{ route('saving-goals.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Buat Target
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($goals as $goal)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-lg">{{ $goal->name }}</h4>
                            @if($goal->target_date)
                            <p class="text-xs text-gray-500">Target: {{ \Carbon\Carbon::parse($goal->target_date)->translatedFormat('d M Y') }}</p>
                            @endif
                        </div>
                        <div class="flex space-x-2 text-sm">
                            <a href="{{ route('saving-goals.edit', $goal->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('saving-goals.destroy', $goal->id) }}" method="POST" onsubmit="return confirm('Hapus target ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Rp {{ number_format($goal->saved_amount, 0, ',', '.') }}</span>
                            <span class="text-gray-500">dari Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $goal->percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $goal->percentage }}% tercapai</p>
                    </div>

                    <a href="{{ route('saving-goals.allocate.form', $goal->id) }}" class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded">
                        + Alokasikan Dana
                    </a>
                </div>
                @empty
                <p class="text-gray-500 col-span-2">Belum ada target tabungan.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
