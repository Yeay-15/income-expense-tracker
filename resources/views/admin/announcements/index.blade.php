<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Broadcast Pengumuman') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium mb-4">Buat Pengumuman Baru</h3>
                <form method="POST" action="{{ route('admin.announcements.store') }}">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="title" value="Judul" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="message" value="Isi Pesan" />
                        <textarea id="message" name="message" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>
                    <x-primary-button>Publikasikan</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Riwayat Pengumuman</h3>
                <div class="space-y-3">
                    @forelse($announcements as $announcement)
                    <div class="border rounded-lg p-4 flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2">
                                <strong>{{ $announcement->title }}</strong>
                                @if($announcement->is_active)
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $announcement->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $announcement->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex flex-col gap-2 text-xs">
                            <form action="{{ route('admin.announcements.toggle', $announcement->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-indigo-600 hover:underline">
                                    {{ $announcement->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Belum ada pengumuman.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
