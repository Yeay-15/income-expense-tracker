<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Master Data: Kategori Default') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium mb-4">Tambah Kategori Default</h3>
                <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-wrap gap-3 items-end">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <x-input-label for="name" value="Nama Kategori" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="type" value="Tipe" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    <x-primary-button>Tambah</x-primary-button>
                </form>
                @error('name')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h4 class="text-sm font-semibold text-green-700 uppercase mb-2">Pemasukan</h4>
                    <ul class="divide-y divide-gray-200">
                        @forelse($categories->where('type', 'income') as $category)
                        <li class="flex justify-between items-center py-2 text-sm">
                            {{ $category->name }}
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Hapus</button>
                            </form>
                        </li>
                        @empty
                        <li class="py-2 text-sm text-gray-500">Belum ada.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h4 class="text-sm font-semibold text-red-700 uppercase mb-2">Pengeluaran</h4>
                    <ul class="divide-y divide-gray-200">
                        @forelse($categories->where('type', 'expense') as $category)
                        <li class="flex justify-between items-center py-2 text-sm">
                            {{ $category->name }}
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Hapus</button>
                            </form>
                        </li>
                        @empty
                        <li class="py-2 text-sm text-gray-500">Belum ada.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
