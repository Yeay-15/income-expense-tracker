<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Alokasikan Dana: ') }}{{ $savingGoal->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('saving-goals.allocate', $savingGoal->id) }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="account_id" value="Ambil Dana dari Akun" />
                        <select id="account_id" name="account_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }} (Rp {{ number_format($account->balance, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('account_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="amount" value="Jumlah Dana (Rp)" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount')" required />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="date" value="Tanggal" />
                        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', date('Y-m-d'))" required />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route(\'saving-goals.index\') }}'">Batal</x-secondary-button>
                        <x-primary-button>Alokasikan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
