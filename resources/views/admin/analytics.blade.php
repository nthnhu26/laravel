<!-- File: resources/views/admin/analytics.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê - Biển Ba Động</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        canvas { max-width: 600px; }
    </style>
</head>
<body>
    <h1>Thống kê Lượt xem</h1>
    ```chartjs
    {
        "type": "bar",
        "data": {
            "labels": {!! json_encode($views->pluck('entity_type')) !!},
            "datasets": [{
                "label": "Lượt xem",
                "data": {!! json_encode($views->pluck('count')) !!},
                "backgroundColor": ["#007bff", "#28a745", "#dc3545", "#ffc107", "#17a2b8"],
                "borderColor": ["#0056b3", "#218838", "#c82333", "#e0a800", "#138496"],
                "borderWidth": 1
            }]
        },
        "options": {
            "scales": {
                "y": {
                    "beginAtZero": true,
                    "title": { "display": true, "text": "Số lượt xem" }
                },
                "x": {
                    "title": { "display": true, "text": "Loại thực thể" }
                }
            },
            "plugins": {
                "legend": { "display": true, "position": "top" }
            }
        }
    }