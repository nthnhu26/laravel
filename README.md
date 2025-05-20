# Tạo dự án Laravel mới
composer create-project laravel/laravel tn_badongbeach
cd tn_badongbeach

# Cấu hình tệp .env
cp .env.example .env  # Sao chép tệp .env.example thành .env để cấu hình môi trường
php artisan key:generate  # Tạo khóa ứng dụng cho Laravel

# Cài đặt các thư viện
## Cài đặt gói Google Translator
composer require stichoza/google-translate-php  # Thư viện hỗ trợ dịch ngôn ngữ

## Cài đặt gói Laravel Translatable
composer require spatie/laravel-translatable  # Thư viện hỗ trợ đa ngôn ngữ cho các model

## Cài đặt gói Laravel Excel
composer require maatwebsite/excel  # Thư viện hỗ trợ xuất/nhập file Excel

## Cài đặt gói Laravel DOMPDF
composer require barryvdh/laravel-dompdf  # Thư viện hỗ trợ xuất file PDF

## Cài đặt gói Laravel Socialite
composer require laravel/socialite  # Thư viện hỗ trợ đăng nhập qua mạng xã hội

## Cài đặt payos
composer require payos/payos

# Cài đặt LocalTunnel để tạo đường dẫn public cho ứng dụng
npm install -g localtunnel  # Cài đặt LocalTunnel toàn cục
lt --port 8000  # Tạo đường dẫn public cho cổng 8000
lt --port 8000 --subdomain nhu26  # Tạo đường dẫn public với subdomain tùy chỉnh

# Cài đặt
composer require php-flasher/flasher-sweetalert-laravel
php artisan flasher:install


# Tạo liên kết symbolic cho thư mục lưu trữ
php artisan storage:link  # Tạo liên kết symbolic từ thư mục storage đến public

# Cài đặt gói
composer require geoip2/geoip2


cập nhật file config/app.php
# laravel
