<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Budget') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-4">
                    {{ $budget->category->name }} — {{ \Carbon\Carbon::create()->month($budget->month)->translatedFormat('F') }} {{ $budget->year }}
                </p>

                <form method="POST" action="{{ route('budgets.update', $budget->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <x-input-label for="amount" value="Batas Anggaran (Rp)" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount', $budget->amount)" required />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route(\'budgets.index\') }}'">Batal</x-secondary-button>
                        <x-primary-button>Update</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
