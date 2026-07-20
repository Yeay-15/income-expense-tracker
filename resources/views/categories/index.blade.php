<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Daftar Kategori</h3>
                        <a href="{{ route('categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                            + Tambah Kategori
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Kolom Income -->
                        <div>
                            <h4 class="text-sm font-semibold text-green-700 uppercase mb-2">Pemasukan</h4>
                            <ul class="divide-y divide-gray-200 border rounded-md">
                                @forelse ($categories->where('type', 'income') as $category)
                                <li class="flex justify-between items-center px-4 py-3">
                                    <span>
                                        {{ $category->name }}
                                        @if(is_null($category->user_id))
                                        <span class="ml-2 text-xs text-gray-400">(default)</span>
                                        @endif
                                    </span>

                                    @if(!is_null($category->user_id))
                                    <div class="flex space-x-2 text-sm">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                    @endif
                                </li>
                                @empty
                                <li class="px-4 py-3 text-sm text-gray-500">Belum ada kategori pemasukan.</li>
                                @endforelse
                            </ul>
                        </div>

                        <!-- Kolom Expense -->
                        <div>
                            <h4 class="text-sm font-semibold text-red-700 uppercase mb-2">Pengeluaran</h4>
                            <ul class="divide-y divide-gray-200 border rounded-md">
                                @forelse ($categories->where('type', 'expense') as $category)
                                <li class="flex justify-between items-center px-4 py-3">
                                    <span>
                                        {{ $category->name }}
                                        @if(is_null($category->user_id))
                                        <span class="ml-2 text-xs text-gray-400">(default)</span>
                                        @endif
                                    </span>

                                    @if(!is_null($category->user_id))
                                    <div class="flex space-x-2 text-sm">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                    @endif
                                </li>
                                @empty
                                <li class="px-4 py-3 text-sm text-gray-500">Belum ada kategori pengeluaran.</li>
                                @endforelse
                            </ul>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
