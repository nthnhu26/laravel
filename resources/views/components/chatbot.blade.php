<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <!-- Chat button -->
    <button id="chatButton" class="btn btn-primary rounded-circle p-3 shadow">
        <i class="bi bi-chat-dots"></i>
    </button>

    <!-- Chat window -->
    <div id="chatWindow" class="d-none position-fixed bottom-0 end-0 p-3" style="width: 400px; height: 600px; z-index: 1040">
        <div class="card h-100 shadow">
            <!-- Chat header -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chat với AI</h5>
                <button id="closeChat" class="btn-close btn-close-white"></button>
            </div>

            <!-- Chat messages -->
            <div id="chatMessages" class="card-body overflow-auto">
                <!-- Messages will be added here dynamically -->
            </div>

            <!-- Chat input -->
            <div class="card-footer">
                <form id="chatForm" class="d-flex gap-2">
                    <input type="text" id="messageInput" class="form-control" placeholder="Nhập tin nhắn...">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatButton = document.getElementById('chatButton');
        const chatWindow = document.getElementById('chatWindow');
        const closeChat = document.getElementById('closeChat');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatMessages = document.getElementById('chatMessages');

        // Toggle chat window
        chatButton.addEventListener('click', () => {
            chatWindow.classList.toggle('d-none');
        });

        closeChat.addEventListener('click', () => {
            chatWindow.classList.add('d-none');
        });

        // Handle form submission
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            // Add user message to chat
            addMessage(message, true);
            messageInput.value = '';

            try {
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message
                    })
                });

                const data = await response.json();

                // Add AI response to chat
                addMessage(data.response, false);
            } catch (error) {
                console.error('Error:', error);
                addMessage('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau.', false);
            }
        });

        // Add message to chat
        function addMessage(message, isUser) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `d-flex ${isUser ? 'justify-content-end' : 'justify-content-start'} mb-3`;

            const messageBubble = document.createElement('div');
            messageBubble.className = `message-bubble ${isUser ? 'bg-primary text-white' : 'bg-light'} rounded p-3`;
            messageBubble.style.maxWidth = '80%';
            messageBubble.textContent = message;

            messageDiv.appendChild(messageBubble);
            chatMessages.appendChild(messageDiv);

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Load chat history if user is authenticated
        async function loadChatHistory() {
            try {
                const response = await fetch('/chat/history');
                const data = await response.json();

                data.history.forEach(message => {
                    addMessage(message.message, message.is_from_user);
                });
            } catch (error) {
                console.error('Error loading chat history:', error);
            }
        }

        // Load chat history on page load
        loadChatHistory();
    });
</script>

<style>
    .message-bubble {
        word-wrap: break-word;
    }
</style>
@endsection