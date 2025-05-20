<!-- File: resources/views/search.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm - Biển Ba Động</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #searchResults { margin-top: 20px; }
        .track-view { color: blue; text-decoration: underline; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Tìm kiếm Dịch vụ tại Biển Ba Động</h1>
    <form id="searchForm">
        <input type="text" name="keyword" placeholder="Nhập từ khóa" required>
        <select name="filters[type]">
            <option value="">Tất cả</option>
            <option value="hotel">Khách sạn</option>
            <option value="restaurant">Nhà hàng</option>
        </select>
        <button type="submit">Tìm kiếm</button>
    </form>

    <div id="searchResults"></div>

    <script>
        $('#searchForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/search',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    let html = '<h2>Kết quả tìm kiếm</h2>';
                    $.each(response.results, function(type, items) {
                        html += `<h3>${type.charAt(0).toUpperCase() + type.slice(1)}</h3><ul>`;
                        items.forEach(item => {
                            html += `<li><a href="/${type}/${item.id}" class="track-view" data-type="${type}" data-id="${item.id}">${item.name.vi}</a></li>`;
                        });
                        html += '</ul>';
                    });
                    $('#searchResults').html(html);
                },
                error: function() {
                    $('#searchResults').html('<p>Lỗi khi tìm kiếm.</p>');
                }
            });
        });

        $(document).on('click', '.track-view', function() {
            let entityType = $(this).data('type');
            let entityId = $(this).data('id');
            $.ajax({
                url: '/api/analytics/track',
                method: 'POST',
                data: {
                    entity_type: entityType,
                    entity_id: entityId,
                    action_type: 'click',
                    page_url: window.location.href
                }
            });
        });
    </script>
</body>
</html>