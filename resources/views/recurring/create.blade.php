<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Jadwalkan Transaksi Berulang') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('recurring.store') }}" x-data="{ frequency: '{{ old('frequency', 'monthly') }}' }">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="account_id" value="Akun" />
                        <select id="account_id" name="account_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="category_id" value="Kategori" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }} ({{ ucfirst($category->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="type" value="Tipe" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="expense">Pengeluaran</option>
                            <option value="income">Pemasukan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="amount" value="Jumlah (Rp)" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" value="Deskripsi (misal: Langganan Spotify)" />
                        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="frequency" value="Frekuensi" />
                        <select id="frequency" name="frequency" x-model="frequency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                            <option value="yearly">Tahunan</option>
                        </select>
                    </div>

                    <div class="mb-4" x-show="frequency === 'monthly'">
                        <x-input-label for="day_of_month" value="Tanggal Setiap Bulan" />
                        <x-text-input id="day_of_month" name="day_of_month" type="number" min="1" max="31" class="mt-1 block w-full" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="start_date" value="Mulai Tanggal" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', date('Y-m-d'))" required />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route(\'recurring.index\') }}'">Batal</x-secondary-button>
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
