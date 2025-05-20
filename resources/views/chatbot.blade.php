<!-- File: resources/views/chatbot.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot - Biển Ba Động</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #chatbox { border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: scroll; }
        #userMessage { width: 70%; padding: 5px; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>
    <h1>Chatbot Du lịch Biển Ba Động</h1>
    <div id="chatbox">
        <div id="messages"></div>
    </div>
    <input type="text" id="userMessage" placeholder="Hỏi tôi về Biển Ba Động...">
    <button onclick="sendMessage()">Gửi</button>

    <script>
        let sessionId = '<?php echo uniqid(); ?>';

        function sendMessage() {
            let message = $('#userMessage').val();
            if (!message) return;

            $('#messages').append(`<p><strong>Bạn:</strong> ${message}</p>`);
            $('#userMessage').val('');

            $.ajax({
                url: '/api/chatbot',
                method: 'POST',
                data: { message, session_id: sessionId },
                success: function(response) {
                    $('#messages').append(`<p><strong>Bot:</strong> ${response.reply}</p>`);
                    $('#messages').scrollTop($('#messages')[0].scrollHeight);
                },
                error: function() {
                    $('#messages').append('<p><strong>Bot:</strong> Lỗi, thử lại nhé.</p>');
                }
            });
        }

        $('#userMessage').keypress(function(e) {
            if (e.which === 13) sendMessage();
        });
    </script>
</body>
</html>