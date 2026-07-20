<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Accounts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                {{-- Search & Filter --}}
                <form method="GET" action="{{ route('admin.accounts.index') }}" class="flex flex-col sm:flex-row gap-3 flex-1">
                    <div class="relative flex-1 max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <select name="role" class="py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="applicant" {{ request('role') === 'applicant' ? 'selected' : '' }}>Applicant</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow text-sm font-medium transition">Search</button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.accounts.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-medium transition text-center">Clear</a>
                    @endif
                </form>

                <a href="{{ route('admin.accounts.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow text-sm font-medium whitespace-nowrap">Add New Account</a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($users->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6 5.87v-2a4 4 0 00-3-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <p class="text-sm">No accounts found.</p>
                            @if(request('search') || request('role'))
                                <a href="{{ route('admin.accounts.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm mt-1 inline-block">Clear filters</a>
                            @endif
                        </div>
                    @else
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

                        {{-- Pagination --}}
                        @if($users->hasPages())
                            <div class="mt-6">
                                {{ $users->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
