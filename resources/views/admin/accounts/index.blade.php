<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-end">
                <a href="{{ route('admin.accounts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow">Add New Account</a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                    <th class="py-4 px-6 font-semibold">Name</th>
                                    <th class="py-4 px-6 font-semibold">Email</th>
                                    <th class="py-4 px-6 font-semibold">Role</th>
                                    <th class="py-4 px-6 font-semibold">Registered</th>
                                    <th class="py-4 px-6 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-4 px-6">{{ $user->name }}</td>
                                        <td class="py-4 px-6">{{ $user->email }}</td>
                                        <td class="py-4 px-6">
                                            <span class="px-2 py-1 rounded text-xs {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">{{ strtoupper($user->role) }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-500">{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td class="py-4 px-6 text-right space-x-4">
                                            <a href="{{ route('admin.accounts.edit', $user) }}" class="text-sm text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('admin.accounts.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this account?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
