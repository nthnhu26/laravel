-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS badong_tourism;
USE badong_tourism;

-- Bảng users (Thông tin người dùng)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NULL, -- NULL cho đăng nhập Google
    google_id VARCHAR(100) UNIQUE NULL, -- ID Google OAuth
    full_name JSON NULL, -- Lưu dạng JSON: {"vi": "...", "en": "..."}
    phone VARCHAR(20) NULL,
    avatar VARCHAR(255) NULL,
    role ENUM('user', 'provider', 'admin') DEFAULT 'user',
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    ban_reason TEXT NULL,
    banned_until DATETIME NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Lưu thông tin người dùng';

-- Bảng user_preferences (Sở thích người dùng)
CREATE TABLE user_preferences (
    user_preference_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    preference_type ENUM('budget', 'luxury', 'beachfront', 'family', 'romantic', 'seafood', 'vegetarian', 'adventure', 'culture', 'relaxation', 'local') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu sở thích người dùng để AI gợi ý';

-- Bảng service_providers (Nhà cung cấp dịch vụ)
CREATE TABLE service_providers (
    provider_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name JSON NOT NULL, -- Lưu dạng JSON
    description JSON NULL,
    address JSON NULL,
    phone VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    website VARCHAR(255) NULL,
    logo VARCHAR(255) NULL,
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    license_number VARCHAR(50) NULL,
    license_file VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin nhà cung cấp dịch vụ';

-- Bảng categories (Danh mục)
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL, -- Lưu dạng JSON
    description JSON NULL,
    parent_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(category_id) ON DELETE SET NULL
) COMMENT 'Lưu danh mục (khách sạn, nhà hàng, địa điểm, tour, bài viết)';

-- Bảng hotels (Thông tin khách sạn)
CREATE TABLE hotels (
    hotel_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL, -- Lưu dạng JSON
    category_id INT NOT NULL,
    provider_id INT NOT NULL,
    description JSON NULL,
    address JSON NULL,
    latitude DECIMAL(10,8) NULL,
    longitude DECIMAL(11,8) NULL,
    price_range VARCHAR(50) NULL, -- Ví dụ: $, $$, $$$, $$$$
    check_in_time TIME NULL,
    check_out_time TIME NULL,
    cancellation_policy JSON NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES service_providers(provider_id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin khách sạn';

-- Bảng rooms (Phòng nghỉ)
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    name JSON NOT NULL, -- Lưu dạng JSON
    description JSON NULL,
    price_per_night DECIMAL(15,2) NOT NULL,
    area DECIMAL(10,2) NULL,
    capacity INT NOT NULL,
    bed_type VARCHAR(100) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(hotel_id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin phòng nghỉ';

-- Bảng restaurants (Nhà hàng)
CREATE TABLE restaurants (
    restaurant_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL,
    type ENUM('restaurant', 'eatery') NOT NULL, -- Phân biệt nhà hàng và quán ăn nhỏ
    provider_id INT NULL,
    description JSON NULL,
    address JSON NULL,
    latitude DECIMAL(10,8) NULL,
    longitude DECIMAL(11,8) NULL,
    opening_hours JSON NULL,
    price_range VARCHAR(50) NULL,
    cancellation_policy JSON NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES service_providers(provider_id) ON DELETE SET NULL
) COMMENT 'Lưu thông tin nhà hàng và quán ăn nhỏ';

-- Bảng attractions (Địa điểm tham quan)
CREATE TABLE attractions (
    attraction_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL,
    type ENUM('beach', 'structure', 'village', 'mangrove', 'historical', 'temple', 'market') NOT NULL, -- Phân loại địa điểm
    description JSON NULL,
    address JSON NULL,
    latitude DECIMAL(10,8) NULL,
    longitude DECIMAL(11,8) NULL,
    opening_hours JSON NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Lưu thông tin địa điểm tham quan (bãi biển, công trình, làng chài, rừng ngập mặn, di tích, thiền viện, chợ)';

-- Bảng dishes (Món ăn)
CREATE TABLE dishes (
    dish_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL, -- Lưu dạng JSON
    restaurant_id INT NOT NULL,
    description JSON NULL,
    price DECIMAL(15,2) NULL,
    price_range VARCHAR(50) NULL,
    dish_type ENUM('seafood', 'local', 'vegetarian', 'drink', 'other') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(restaurant_id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin món ăn';

-- Bảng tours (Tour du lịch)
CREATE TABLE tours (
    tour_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL, -- Lưu dạng JSON
    description JSON NULL,
    duration_days INT NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    max_people INT NULL,
    provider_id INT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES service_providers(provider_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin tour du lịch';

-- Bảng tour_details (Chi tiết lịch trình tour)
CREATE TABLE tour_details (
    tour_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    day_number INT NOT NULL,
    attraction_id INT NULL,
    description JSON NOT NULL, -- Lưu dạng JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(tour_id) ON DELETE CASCADE,
    FOREIGN KEY (attraction_id) REFERENCES attractions(attraction_id) ON DELETE SET NULL
) COMMENT 'Lưu chi tiết lịch trình tour';

-- Bảng transports (Phương tiện di chuyển)
CREATE TABLE transports (
    transport_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL, -- Lưu dạng JSON
    type ENUM('car', 'motorbike', 'bicycle', 'boat') NOT NULL,
    capacity INT NOT NULL,
    price_per_day DECIMAL(15,2) NOT NULL,
    provider_id INT NULL,
    status ENUM('available', 'booked') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES service_providers(provider_id) ON DELETE SET NULL
) COMMENT 'Lưu thông tin phương tiện di chuyển';

-- Bảng amenities (Tiện ích)
CREATE TABLE amenities (
    amenity_id INT AUTO_INCREMENT PRIMARY KEY,
    name JSON NOT NULL, -- Lưu dạng JSON
    description JSON NULL,
    icon VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Lưu tiện ích (Wi-Fi, hồ bơi, ...)';

-- Bảng amenity_entity (Liên kết tiện ích)
CREATE TABLE amenity_entity (
    amenity_entity_id INT AUTO_INCREMENT PRIMARY KEY,
    amenity_id INT NOT NULL,
    entity_type ENUM('hotel', 'room', 'restaurant', 'attraction', 'tour', 'transport') NOT NULL,
    entity_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (amenity_id) REFERENCES amenities(amenity_id) ON DELETE CASCADE
) COMMENT 'Liên kết tiện ích với khách sạn, phòng, nhà hàng, địa điểm, tour, phương tiện';

-- Bảng images (Quản lý hình ảnh)
CREATE TABLE images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    entity_type ENUM('hotel', 'room', 'restaurant', 'attraction', 'dish', 'tour', 'transport', 'review', 'post', 'event') NOT NULL,
    entity_id INT NOT NULL,
    url VARCHAR(255) NOT NULL,
    caption TEXT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Lưu hình ảnh cho các thực thể';

-- Bảng bookings (Đặt dịch vụ)
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_type ENUM('hotel', 'tour', 'transport', 'restaurant') NOT NULL,
    service_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NULL,
    number_of_people INT NOT NULL,
    special_requests TEXT NULL,
    total_price DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin đặt dịch vụ';


-- Bảng reviews (Đánh giá - đơn giản hóa)
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    entity_type ENUM('hotel', 'room', 'restaurant', 'attraction', 'dish', 'tour', 'transport') NOT NULL,
    entity_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu đánh giá từ khách du lịch';

-- Bảng favorites (Yêu thích)
CREATE TABLE favorites (
    favorite_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    entity_type ENUM('hotel', 'room', 'restaurant', 'attraction', 'dish', 'tour', 'transport') NOT NULL,
    entity_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu địa điểm/dịch vụ yêu thích';

-- Bảng itineraries (Lịch trình cá nhân)
CREATE TABLE itineraries (
    itinerary_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    share_token VARCHAR(100) UNIQUE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu lịch trình cá nhân';

-- Bảng itinerary_details (Chi tiết lịch trình)
CREATE TABLE itinerary_details (
    itinerary_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    itinerary_id INT NOT NULL,
    entity_type ENUM('hotel', 'restaurant', 'attraction', 'dish', 'tour', 'transport') NOT NULL,
    entity_id INT NOT NULL,
    visit_date DATE NOT NULL,
    notes TEXT NULL,
    estimated_cost DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (itinerary_id) REFERENCES itineraries(itinerary_id) ON DELETE CASCADE
) COMMENT 'Lưu chi tiết lịch trình cá nhân';

-- Bảng posts (Bài viết - không đa ngôn ngữ)
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    type ENUM('news', 'guide', 'review') NULL,
    attraction_id INT NULL,
    status ENUM('draft', 'published', 'rejected') DEFAULT 'draft',
    rejection_reason TEXT NULL,
    tags VARCHAR(255) NULL,
    short_description TEXT NULL,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (attraction_id) REFERENCES attractions(attraction_id) ON DELETE SET NULL
) COMMENT 'Lưu bài viết quảng bá';

-- Bảng post_comments (Bình luận bài viết)
CREATE TABLE post_comments (
    post_comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    parent_id INT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES post_comments(post_comment_id) ON DELETE SET NULL
) COMMENT 'Lưu bình luận bài viết';

-- Bảng events (Sự kiện)
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title JSON NOT NULL, -- Lưu dạng JSON
    description JSON NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    location JSON NULL,
    attraction_id INT NULL,
    status ENUM('upcoming', 'ongoing', 'completed') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (attraction_id) REFERENCES attractions(attraction_id) ON DELETE SET NULL
) COMMENT 'Lưu thông tin sự kiện';

-- Bảng notifications (Thông báo)
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('booking', 'system', 'other') NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    related_entity_type ENUM('booking', 'restaurant', 'attraction', 'hotel', 'tour', 'transport') NULL,
    related_entity_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) COMMENT 'Lưu thông báo tự động cho người dùng';

-- Bảng chatbot_conversations (Phiên hội thoại chatbot)
CREATE TABLE chatbot_conversations (
    conversation_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    user_id INT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ended_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) COMMENT 'Lưu phiên hội thoại chatbot';

-- Bảng chatbot_messages (Tin nhắn chatbot)
CREATE TABLE chatbot_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    is_from_user BOOLEAN NOT NULL,
    message TEXT NOT NULL,
    intent_name VARCHAR(100) NULL,
    entities TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES chatbot_conversations(conversation_id) ON DELETE CASCADE
) COMMENT 'Lưu tin nhắn chatbot';

-- Bảng contacts (Liên hệ)
CREATE TABLE contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Lưu thông tin liên hệ';

-- Bảng analytics (Thống kê)
CREATE TABLE analytics (
    analytic_id INT AUTO_INCREMENT PRIMARY KEY,
    entity_type ENUM('hotel', 'room', 'restaurant', 'attraction', 'dish', 'tour', 'transport', 'post', 'event') NOT NULL,
    entity_id INT NOT NULL,
    user_id INT NULL,
    action_type ENUM('view', 'click') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) COMMENT 'Lưu thống kê lượt xem/click';

-- Bảng password_reset_tokens (Token đặt lại mật khẩu)
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) COMMENT 'Lưu token đặt lại mật khẩu';

-- Bảng sessions (Phiên đăng nhập)
CREATE TABLE sessions (
    session_id VARCHAR(255) PRIMARY KEY,
    user_id INT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL
) COMMENT 'Lưu thông tin phiên đăng nhập';
