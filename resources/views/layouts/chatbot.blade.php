<div id="chatbot" class="chatbot-container">
    <div class="chatbot-header">
        <h5>Trợ lý du lịch Ba Động</h5>
        <button id="chatbot-toggle" title="Thu gọn">×</button>
    </div>
    <div id="chatbot-messages" class="chatbot-messages"></div>
    <div class="chatbot-suggestions">
        <button class="btn btn-sm btn-outline-primary chatbot-suggestion" data-message="Thời tiết ở Biển Ba Động hôm nay?">Thời tiết</button>
        <button class="btn btn-sm btn-outline-primary chatbot-suggestion" data-message="Tìm nhà hàng hải sản giá rẻ">Nhà hàng</button>
        <button class="btn btn-sm btn-outline-primary chatbot-suggestion" data-message="Khách sạn giá rẻ gần biển">Khách sạn</button>
    </div>
    <div class="chatbot-input-group input-group">
        <input type="text" id="chatbot-input" class="form-control" placeholder="Hỏi gì đi...">
        <button id="chatbot-send" class="btn btn-primary">Gửi</button>
    </div>
</div>
<script src="{{ asset('assets/js/chatbot.js') }}"></script>