<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Assistants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Assistant Management</h3>
                        <p class="mt-1 text-sm text-gray-600">Manage your AI writing assistants</p>
                    </div>
                    <button onclick="openAddAssistantModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Add New Assistant
                    </button>
                </div>

                <!-- Assistant Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($assistants as $assistant)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $assistant->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $assistant->model }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 line-clamp-2">{{ $assistant->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($assistant->status === 'active') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($assistant->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editAssistant({{ $assistant->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button onclick="deleteAssistant({{ $assistant->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Assistant Modal -->
    <div id="assistantModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add New Assistant</h3>
                    <form id="assistantForm" class="mt-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="assistantName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model</label>
                                <select name="model" id="assistantModel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="gpt-4">GPT-4</option>
                                    <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="assistantDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Instructions</label>
                                <textarea name="instructions" id="assistantInstructions" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <p class="mt-1 text-sm text-gray-500">Provide detailed instructions for the assistant's behavior and capabilities.</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button onclick="closeAssistantModal()" class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button onclick="saveAssistant()" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentAssistantId = null;

        function openAddAssistantModal() {
            currentAssistantId = null;
            document.getElementById('modalTitle').textContent = 'Add New Assistant';
            document.getElementById('assistantForm').reset();
            document.getElementById('assistantModal').classList.remove('hidden');
        }

        function editAssistant(assistantId) {
            currentAssistantId = assistantId;
            document.getElementById('modalTitle').textContent = 'Edit Assistant';
            
            fetch(`/api/assistants/${assistantId}`)
                .then(response => response.json())
                .then(assistant => {
                    document.getElementById('assistantName').value = assistant.name;
                    document.getElementById('assistantModel').value = assistant.model;
                    document.getElementById('assistantDescription').value = assistant.description;
                    document.getElementById('assistantInstructions').value = assistant.instructions;
                });

            document.getElementById('assistantModal').classList.remove('hidden');
        }

        function closeAssistantModal() {
            document.getElementById('assistantModal').classList.add('hidden');
        }

        function saveAssistant() {
            const formData = {
                name: document.getElementById('assistantName').value,
                model: document.getElementById('assistantModel').value,
                description: document.getElementById('assistantDescription').value,
                instructions: document.getElementById('assistantInstructions').value,
            };

            const url = currentAssistantId ? `/api/assistants/${currentAssistantId}` : '/api/assistants';
            const method = currentAssistantId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(() => {
                closeAssistantModal();
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to save assistant. Please try again.');
            });
        }

        function deleteAssistant(assistantId) {
            if (confirm('Are you sure you want to delete this assistant? This will also delete the assistant from OpenAI.')) {
                fetch(`/api/assistants/${assistantId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(() => window.location.reload())
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete assistant. Please try again.');
                });
            }
        }
    </script>
    @endpush
</x-app-layout>
