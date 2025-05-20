/* public/js/chatbot.js */
document.addEventListener('DOMContentLoaded', () => {
    const chatbot = document.getElementById('chatbot');
    const messages = document.getElementById('chatbot-messages');
    const input = document.getElementById('chatbot-input');
    const sendButton = document.getElementById('chatbot-send');
    const toggleButton = document.getElementById('chatbot-toggle');
    const suggestionButtons = document.querySelectorAll('.chatbot-suggestion');
    let isOpen = true;

    // Gửi tin nhắn
    function sendMessage(messageText) {
        const message = messageText || input.value.trim();
        if (!message) return;

        // Hiển thị tin nhắn người dùng
        const userMessage = document.createElement('div');
        userMessage.classList.add('chatbot-message', 'user');
        userMessage.innerHTML = message;
        messages.appendChild(userMessage);
        input.value = '';
        messages.scrollTop = messages.scrollHeight;

        // Gửi yêu cầu đến server
        fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message }),
        })
            .then(response => response.json())
            .then(data => {
                const botMessage = document.createElement('div');
                botMessage.classList.add('chatbot-message', 'bot');
                botMessage.innerHTML = data.reply;
                messages.appendChild(botMessage);
                messages.scrollTop = messages.scrollHeight;
            })
            .catch(() => {
                const errorMessage = document.createElement('div');
                errorMessage.classList.add('chatbot-message', 'bot');
                errorMessage.innerHTML = 'Lỗi, vui lòng thử lại!';
                messages.appendChild(errorMessage);
                messages.scrollTop = messages.scrollHeight;
            });
    }

    // Thu gọn/mở rộng chatbot
    toggleButton.addEventListener('click', () => {
        isOpen = !isOpen;
        chatbot.classList.toggle('chatbot-collapsed', !isOpen);
    });

    // Gửi tin nhắn bằng nút
    sendButton.addEventListener('click', () => sendMessage());

    // Gửi tin nhắn bằng Enter
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    // Nút gợi ý
    suggestionButtons.forEach(button => {
        button.addEventListener('click', () => {
            sendMessage(button.dataset.message);
        });
    });

    // Tin nhắn chào mừng
    const welcomeMessage = document.createElement('div');
    welcomeMessage.classList.add('chatbot-message', 'bot');
    welcomeMessage.innerHTML = 'Xin chào! Tôi là trợ lý du lịch Biển Ba Động. Hỏi tôi về thời tiết, nhà hàng, hay địa điểm nhé!';
    messages.appendChild(welcomeMessage);
});