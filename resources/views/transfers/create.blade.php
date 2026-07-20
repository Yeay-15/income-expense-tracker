<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Transfer Antar Akun') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('transfers.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="from_account_id" value="Dari Akun" />
                        <select id="from_account_id" name="from_account_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="" disabled selected>Pilih akun asal</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }} (Rp {{ number_format($account->balance, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="to_account_id" value="Ke Akun" />
                        <select id="to_account_id" name="to_account_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="" disabled selected>Pilih akun tujuan</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="amount" value="Jumlah" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount')" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" value="Catatan (opsional)" />
                        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="date" value="Tanggal" />
                        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', date('Y-m-d'))" required />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route('transactions.index') }}'">Batal</x-secondary-button>
                        <x-primary-button>Transfer</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>