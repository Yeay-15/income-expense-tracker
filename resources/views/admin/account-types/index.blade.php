<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Master Data: Jenis Akun') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium mb-4">Tambah Jenis Akun</h3>
                <form method="POST" action="{{ route('admin.account-types.store') }}" class="flex gap-3 items-end">
                    @csrf
                    <div class="flex-1">
                        <x-input-label for="name" value="Nama Jenis Akun (misal: Kartu Kredit)" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <x-primary-button>Tambah</x-primary-button>
                </form>
                @error('name')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Daftar Jenis Akun</h3>
                <ul class="divide-y divide-gray-200">
                    @forelse($accountTypes as $type)
                    <li class="flex justify-between items-center py-3 text-sm">
                        <span>{{ $type->name }} <span class="text-gray-400 text-xs">({{ $type->accounts_count }} akun terpakai)</span></span>
                        <form action="{{ route('admin.account-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('Hapus jenis akun ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Hapus</button>
                        </form>
                    </li>
                    @empty
                    <li class="py-3 text-sm text-gray-500">Belum ada jenis akun.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
