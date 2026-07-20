<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Account -->
                        <div class="mb-4">
                            <label for="account_id" class="block text-sm font-medium text-gray-700">Account</label>
                            <select name="account_id" id="account_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" required>
                                <option value="" disabled {{ old('account_id', $transaction->account_id) ? '' : 'selected' }}>Select account</option>
                                @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} ({{ $account->accountType->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('account_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" required>
                                <option value="" disabled {{ old('category_id', $transaction->category_id) ? '' : 'selected' }}>Select category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    data-type="{{ $category->type }}"
                                    {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ ucfirst($category->type) }})
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Receipt Upload & Preview -->
                        <div class="mb-4">
                            @if($transaction->receipt_path)
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 mb-1">Current receipt:</p>
                                <img src="{{ Storage::url($transaction->receipt_path) }}" class="h-32 rounded border object-cover">
                            </div>
                            @endif

                            <label for="receipt" class="block text-sm font-medium text-gray-700">Receipt (optional - upload new to replace)</label>
                            <input type="file" name="receipt" id="receipt" accept="image/*" class="mt-1 block w-full text-sm">
                            @error('receipt')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transaction Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="income" {{ (old('type', $transaction->type) == 'income') ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ (old('type', $transaction->type) == 'expense') ? 'selected' : '' }}>Expense</option>
                            </select>
                            @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (Rp)</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $transaction->amount) }}" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <input type="text" name="description" id="description" value="{{ old('description', $transaction->description) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="mb-6">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $transaction->date) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                                Update Transaction
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
