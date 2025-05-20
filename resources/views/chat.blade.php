<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div id="chat-container" class="h-96 overflow-y-auto mb-4 p-4 border rounded">
                        <!-- Chat messages will appear here -->
                    </div>

                    <div class="flex gap-2">
                        <input type="text" id="message-input"
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Nhập tin nhắn của bạn...">
                        <button onclick="sendMessage()"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Gửi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const chatContainer = document.getElementById('chat-container');
        const messageInput = document.getElementById('message-input');

        // Load chat history when page loads
        window.onload = function() {
            loadChatHistory();
        };

        function loadChatHistory() {
            fetch('/api/chat/history')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.history.forEach(message => {
                            appendMessage(message.message, message.is_user);
                        });
                    }
                });
        }

        function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;

            // Add user message to chat
            appendMessage(message, true);
            messageInput.value = '';

            // Send message to server
            fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        appendMessage(data.message, false);
                    }
                });
        }

        function appendMessage(message, isUser) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-4 ${isUser ? 'text-right' : 'text-left'}`;

            const messageBubble = document.createElement('div');
            messageBubble.className = `inline-block p-3 rounded-lg ${
                isUser ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'
            }`;
            messageBubble.textContent = message;

            messageDiv.appendChild(messageBubble);
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Allow sending message with Enter key
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
    @endpush
</x-app-layout>