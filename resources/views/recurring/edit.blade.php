<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Transaksi Berulang') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('recurring.update', $recurring->id) }}"
                    x-data="{
                        type: '{{ old('type', $recurring->type) }}',
                        frequency: '{{ old('frequency', $recurring->frequency) }}',
                        categories: {{ $categories->toJson() }},
                        selectedCategory: '{{ old('category_id', $recurring->category_id) }}',
                        get filteredCategories() {
                            return this.categories
                                .filter(category => category.type === this.type)
                                .sort((a, b) => {
                                    let aLainnya = a.name.toLowerCase().includes('lainnya') ? 1 : 0;
                                    let bLainnya = b.name.toLowerCase().includes('lainnya') ? 1 : 0;
                                    return aLainnya - bLainnya;
                                });
                        }
                    }"
                    x-init="
                        $watch('type', value => {
                            const available = filteredCategories;
                            if (available.length > 0) {
                                selectedCategory = available[0].id;
                            } else {
                                selectedCategory = '';
                            }
                        });
                    ">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="is_active" value="Status Jadwal" />
                        <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1" {{ old('is_active', $recurring->is_active) ? 'selected' : '' }}>Aktif (Berjalan Otomatis)</option>
                            <option value="0" {{ !old('is_active', $recurring->is_active) ? 'selected' : '' }}>Nonaktif (Jeda Sementara)</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="account_id" value="Akun" />
                        <select id="account_id" name="account_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_id', $recurring->account_id) == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('account_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="type" value="Tipe Transaksi" />
                        <select id="type" name="type" x-model="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="expense">Pengeluaran (Expense)</option>
                            <option value="income">Pemasukan (Income)</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="category_id" value="Kategori" />
                        <select id="category_id" name="category_id" x-model="selectedCategory" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <template x-for="category in filteredCategories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        <p x-show="filteredCategories.length === 0" class="text-xs text-red-500 mt-1">Tidak ada kategori untuk tipe ini.</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="amount" value="Jumlah (Rp)" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount', $recurring->amount)" required />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" value="Deskripsi (misal: Langganan Spotify)" />
                        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $recurring->description)" required />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="frequency" value="Frekuensi" />
                        <select id="frequency" name="frequency" x-model="frequency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                            <option value="yearly">Tahunan</option>
                        </select>
                        <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
                    </div>

                    <div class="mb-6" x-show="frequency === 'monthly'">
                        <x-input-label for="day_of_month" value="Tanggal Setiap Bulan" />
                        <x-text-input id="day_of_month" name="day_of_month" type="number" min="1" max="31" class="mt-1 block w-full" :value="old('day_of_month', $recurring->day_of_month)" />
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika ingin dieksekusi menyesuaikan dengan tanggal terakhir berjalan.</p>
                        <x-input-error :messages="$errors->get('day_of_month')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route('recurring.index') }}'">Batal</x-secondary-button>
                        <x-primary-button>Perbarui Jadwal</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>