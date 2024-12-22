@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 dark:text-white">Create New Research Paper</h1>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <strong>Please correct the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Assistant Selection -->
                <div>
                    <label for="assistant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Research Assistant</label>
                    <select name="assistant_id" id="assistant_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        <option value="">Select a research assistant...</option>
                        @foreach($assistants as $assistant)
                            <option value="{{ $assistant->id }}" {{ old('assistant_id') == $assistant->id ? 'selected' : '' }}>
                                {{ $assistant->name }} - {{ $assistant->description }}
                            </option>
                        @endforeach
                    </select>
                    @error('assistant_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Research Type -->
                <div>
                    <label for="research_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type of Research Paper</label>
                    <select name="research_type" id="research_type" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        <option value="">Select a research type...</option>
                        @foreach(\App\Models\Task::$researchTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('research_type') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('research_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Research Method -->
                <div>
                    <label for="research_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Research Method</label>
                    <select name="research_method" id="research_method" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        <option value="">Select a research method...</option>
                        @foreach(\App\Models\Task::$researchMethods as $value => $label)
                            <option value="{{ $value }}" {{ old('research_method') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('research_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field of Study -->
                <div>
                    <label for="field_study" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field of Study</label>
                    <input type="text" name="field_study" id="field_study" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           value="{{ old('field_study') }}" 
                           placeholder="e.g., Computer Science, Psychology, Economics"
                           required>
                    @error('field_study')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Theme/Topic -->
                <div>
                    <label for="theme" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Research Theme/Topic</label>
                    <input type="text" name="theme" id="theme" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           value="{{ old('theme') }}" 
                           placeholder="Main theme or topic of your research"
                           required>
                    @error('theme')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Research Title</label>
                    <input type="text" name="title" id="title" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           value="{{ old('title') }}" 
                           placeholder="Enter a descriptive title for your research paper"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Abstract -->
                <div>
                    <label for="abstract" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Abstract</label>
                    <textarea name="abstract" id="abstract" rows="6" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder="Provide a brief overview of your research paper, including the problem statement, methodology, and expected outcomes."
                              required>{{ old('abstract') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Write a clear and concise summary of your research paper (recommended: 150-250 words)
                    </p>
                    @error('abstract')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('tasks.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Create Research Paper
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
