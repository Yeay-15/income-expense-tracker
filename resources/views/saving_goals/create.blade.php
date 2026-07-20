<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Buat Target Tabungan') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('saving-goals.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" value="Nama Target (misal: Beli Laptop)" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="target_amount" value="Jumlah Target (Rp)" />
                        <x-text-input id="target_amount" name="target_amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('target_amount')" required />
                        <x-input-error :messages="$errors->get('target_amount')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="target_date" value="Target Tanggal (opsional)" />
                        <x-text-input id="target_date" name="target_date" type="date" class="mt-1 block w-full" :value="old('target_date')" />
                        <x-input-error :messages="$errors->get('target_date')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route('saving-goals.index') }}'">Batal</x-secondary-button>
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
