<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Research Papers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold dark:text-white">Research Papers</h1>
                    <button onclick="openAddTaskModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        New Research Paper
                    </button>
                </div>

                @if($tasks->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">You haven't created any research papers yet.</p>
                    </div>
                @else
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($tasks as $task)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h2>
                                        <span class="px-2 py-1 text-sm rounded-full 
                                            {{ $task->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                               'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                            {{ \App\Models\Task::$statuses[$task->status] ?? 'Unknown' }}
                                        </span>
                                    </div>

                                    <div class="space-y-2 mb-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Theme:</span> {{ $task->theme }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Field:</span> {{ $task->field_study }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Type:</span> {{ \App\Models\Task::$researchTypes[$task->research_type] ?? 'Unknown' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Method:</span> {{ \App\Models\Task::$researchMethods[$task->research_method] ?? 'Unknown' }}
                                        </p>
                                    </div>

                                    <div class="border-t dark:border-gray-700 pt-4">
                                        <div class="flex justify-between">
                                            <a href="{{ route('chat.show', $task) }}" 
                                               class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300">
                                                Continue Writing
                                            </a>
                                            <div class="flex space-x-2">
                                                <button onclick="editTask({{ $task->id }})" class="text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300">
                                                    Edit
                                                </button>
                                                <button onclick="deleteTask({{ $task->id }})" 
                                                        class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300"
                                                        onclick="return confirm('Are you sure you want to delete this research paper?')">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add/Edit Task Modal -->
    <div id="taskModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add New Task</h3>
                    <form id="taskForm" class="mt-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="title" id="taskTitle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Theme</label>
                                <input type="text" name="theme" id="taskTheme" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Field of Study</label>
                                <input type="text" name="field_study" id="taskFieldStudy" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Research Type</label>
                                <select name="research_type" id="taskResearchType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(\App\Models\Task::$researchTypes as $type => $typeName)
                                        <option value="{{ $type }}">{{ $typeName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Research Method</label>
                                <select name="research_method" id="taskResearchMethod" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(\App\Models\Task::$researchMethods as $method => $methodName)
                                        <option value="{{ $method }}">{{ $methodName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(auth()->user()->role === 'admin')
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="taskStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button onclick="closeTaskModal()" class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button onclick="saveTask()" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentTaskId = null;

        function openAddTaskModal() {
            currentTaskId = null;
            document.getElementById('modalTitle').textContent = 'Add New Task';
            document.getElementById('taskForm').reset();
            document.getElementById('taskModal').classList.remove('hidden');
        }

        function editTask(taskId) {
            currentTaskId = taskId;
            document.getElementById('modalTitle').textContent = 'Edit Task';
            
            fetch(`/api/tasks/${taskId}`)
                .then(response => response.json())
                .then(task => {
                    document.getElementById('taskTitle').value = task.title;
                    document.getElementById('taskTheme').value = task.theme;
                    document.getElementById('taskFieldStudy').value = task.field_study;
                    document.getElementById('taskResearchType').value = task.research_type;
                    document.getElementById('taskResearchMethod').value = task.research_method;
                    if (document.getElementById('taskStatus')) {
                        document.getElementById('taskStatus').value = task.status;
                    }
                });

            document.getElementById('taskModal').classList.remove('hidden');
        }

        function closeTaskModal() {
            document.getElementById('taskModal').classList.add('hidden');
        }

        function saveTask() {
            const formData = {
                title: document.getElementById('taskTitle').value,
                theme: document.getElementById('taskTheme').value,
                field_study: document.getElementById('taskFieldStudy').value,
                research_type: document.getElementById('taskResearchType').value,
                research_method: document.getElementById('taskResearchMethod').value,
            };

            if (document.getElementById('taskStatus')) {
                formData.status = document.getElementById('taskStatus').value;
            }

            const url = currentTaskId ? `/api/tasks/${currentTaskId}` : '/api/tasks';
            const method = currentTaskId ? 'PUT' : 'POST';

            // Disable the save button and show loading state
            const saveButton = document.querySelector('button[onclick="saveTask()"]');
            const originalText = saveButton.textContent;
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.error || 'Failed to save task');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'An error occurred. Please try again.');
            })
            .finally(() => {
                // Re-enable the save button and restore original text
                saveButton.disabled = false;
                saveButton.textContent = originalText;
            });
        }

        function deleteTask(taskId) {
            if (!confirm('Are you sure you want to delete this research paper?')) {
                return;
            }

            fetch(`/api/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to delete task');
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete task. Please try again.');
            });
        }
    </script>
    @endpush
</x-app-layout>
