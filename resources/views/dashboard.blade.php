<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- User Management Card -->
                @if(auth()->user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-indigo-500 rounded-full">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">User Management</h2>
                                <p class="mt-1 text-sm text-gray-600">Manage system users and their roles.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Total Users: {{ $totalUsers }}</p>
                            <p class="text-sm text-gray-600">Admin Users: {{ $adminUsers }}</p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Manage Users
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tasks Card -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-500 rounded-full">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">Tasks</h2>
                                <p class="mt-1 text-sm text-gray-600">Manage your writing tasks.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Total Tasks: {{ $totalTasks }}</p>
                            <p class="text-sm text-gray-600">Active Tasks: {{ $activeTasks }}</p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                View Tasks
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Assistants Card -->
                @if(auth()->user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-500 rounded-full">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900">AI Assistants</h2>
                                <p class="mt-1 text-sm text-gray-600">Manage AI writing assistants.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Total Assistants: {{ $totalAssistants }}</p>
                            <p class="text-sm text-gray-600">Active Assistants: {{ $activeAssistants }}</p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('assistants.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                Manage Assistants
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
