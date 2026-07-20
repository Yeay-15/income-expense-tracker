<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Akun') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('accounts.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="account_type_id" value="Jenis Akun" />
                        <select id="account_type_id" name="account_type_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($accountTypes as $type)
                            <option value="{{ $type->id }}" {{ old('account_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('account_type_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="name" value="Nama Akun (misal: BCA Utama)" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="initial_balance" value="Saldo Awal" />
                        <x-text-input id="initial_balance" name="initial_balance" type="number" step="0.01" class="mt-1 block w-full" :value="old('initial_balance', 0)" required />
                        <x-input-error :messages="$errors->get('initial_balance')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route('accounts.index') }}'">Batal</x-secondary-button>
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
