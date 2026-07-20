<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Accounts') }}
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
                        <h3 class="text-lg font-medium">Daftar Akun / Dompet</h3>
                        <a href="{{ route('accounts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                            + Tambah Akun
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse ($accounts as $account)
                        <div class="border rounded-lg p-4 shadow-sm">
                            <div class="text-xs text-gray-500 uppercase">{{ $account->accountType->name }}</div>
                            <div class="text-lg font-semibold">{{ $account->name }}</div>
                            <div class="text-2xl font-bold mt-2 {{ $account->balance < 0 ? 'text-red-600' : 'text-gray-900' }}">
                                Rp {{ number_format($account->balance, 0, ',', '.') }}
                            </div>

                            <div class="flex space-x-2 mt-4 text-sm">
                                <a href="{{ route('accounts.edit', $account->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Yakin hapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 col-span-3">Belum ada akun. Tambahkan akun pertama Anda.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
