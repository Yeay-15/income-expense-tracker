<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-red-600 mb-4">Welcome to Admin Area!</h3>
                    <p class="mb-4">This section is strictly restricted. Only users with the <strong>Admin</strong> role can see this page.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>