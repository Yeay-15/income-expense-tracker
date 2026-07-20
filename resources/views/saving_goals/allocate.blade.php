<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Alokasikan Dana untuk: ') }} {{ $savingGoal->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('saving-goals.allocate', $savingGoal->id) }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="account_id" value="Ambil Dana dari Akun" />
                            <select id="account_id" name="account_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="" disabled selected>-- Pilih Akun Sumber Dana --</option>
                                @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} (Saldo: Rp {{ number_format($account->balance, 0, ',', '.') }})
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('account_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="amount" value="Jumlah Dana yang Dialokasikan (Rp)" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount')" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="date" value="Tanggal Transaksi" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('saving-goals.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan Alokasi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>