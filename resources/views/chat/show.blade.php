@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold dark:text-white">Chat with Assistant</h1>
        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    <div id="chat-messages" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 mb-4 h-[600px] overflow-y-auto">
        @foreach($messages->data as $message)
            <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }} mb-4 message-fade-in">
                <div class="max-w-xl {{ $message->role === 'user' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-100 dark:bg-gray-700' }} rounded-lg p-3">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ ucfirst($message->role) }}
                    </div>
                    <div class="text-gray-800 dark:text-gray-200 mt-1">
                        {{ trim($message->content[0]->text->value) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form id="chat-form" class="flex gap-2">
        <input type="text" id="message-input" 
               class="flex-1 rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
               placeholder="Type your message...">
        <button type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors">
            Send
        </button>
    </form>
</div>

@push('scripts')
<script>
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const darkModeToggle = document.getElementById('darkModeToggle');

    // Dark mode toggle
    darkModeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
    });

    // Scroll to bottom of chat
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Add a message to the chat
    function addMessage(message, role) {
        const div = document.createElement('div');
        div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'} mb-4 message-fade-in`;
        div.innerHTML = `
            <div class="max-w-xl ${role === 'user' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-100 dark:bg-gray-700'} rounded-lg p-3">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    ${role.charAt(0).toUpperCase() + role.slice(1)}
                </div>
                <div class="text-gray-800 dark:text-gray-200 mt-1">
                    ${message.trim().replace(/\n/g, '<br>')}
                </div>
            </div>
        `;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    // Add a status message with thinking animation
    function addStatusMessage(message) {
        // Remove any existing status messages first
        const existingStatus = chatMessages.querySelectorAll('.status-message');
        existingStatus.forEach(el => el.remove());

        const div = document.createElement('div');
        div.className = 'flex justify-center my-2 status-message message-fade-in';
        div.innerHTML = `
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-700">
                <span class="text-sm text-gray-600 dark:text-gray-400">${message}</span>
                <span class="ml-2 flex space-x-1">
                    <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                    <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                </span>
            </div>
        `;
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    // Handle form submission
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;

        // Clear input and disable form
        messageInput.value = '';
        const submitButton = chatForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        messageInput.disabled = true;

        // Add user message to chat
        addMessage(message, 'user');
        addStatusMessage('Assistant is thinking');

        try {
            const response = await fetch(`/tasks/{{ $task->id }}/chat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }

            // Remove the status message
            const statusMessage = chatMessages.querySelector('.status-message');
            if (statusMessage) {
                statusMessage.remove();
            }

            // Add only the new assistant message
            const latestMessage = data.messages[0];
            if (latestMessage && latestMessage.role === 'assistant') {
                addMessage(latestMessage.content[0].text.value, 'assistant');
            }

        } catch (error) {
            console.error('Error:', error);
            addStatusMessage(`Error: ${error.message}`);
        } finally {
            // Re-enable the form
            submitButton.disabled = false;
            messageInput.disabled = false;
            messageInput.focus();
        }
    });

    // Initial scroll to bottom
    scrollToBottom();
</script>

<style>
    /* Message fade in animation */
    .message-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Hide scrollbar but keep functionality */
    #chat-messages {
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    #chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    #chat-messages::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }
</style>
@endpush
@endsection
