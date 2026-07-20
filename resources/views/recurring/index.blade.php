<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Transaksi Berulang') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium">Daftar Jadwal</h3>
                <a href="{{ route('recurring.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Jadwalkan Transaksi
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frekuensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal Berikutnya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recurrings as $recurring)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $recurring->description }}</td>
                            <td class="px-6 py-4 text-sm">Rp {{ number_format($recurring->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm capitalize">{{ $recurring->frequency }}</td>
                            <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($recurring->next_run_date)->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($recurring->is_active)
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('recurring.edit', $recurring->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('recurring.destroy', $recurring->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-sm">Belum ada jadwal.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
