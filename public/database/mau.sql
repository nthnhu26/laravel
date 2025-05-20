-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS badong_tourism;
USE badong_tourism;



-- Insert dữ liệu mẫu cho users
INSERT INTO users (email, password, google_id, full_name, phone, avatar, role, status, ban_reason, banned_until, email_verified_at, remember_token) VALUES
('user1@example.com', '$2y$10$hashedpassword1', NULL, '{"vi": "Nguyễn Văn A", "en": "Nguyen Van A"}', '0901234561', 'avatars/user1.jpg', 'user', 'active', NULL, NULL, NOW(), NULL),
('user2@example.com', NULL, 'google123', '{"vi": "Trần Thị B", "en": "Tran Thi B"}', '0901234562', 'avatars/user2.jpg', 'provider', 'active', NULL, NULL, NOW(), NULL),
('user3@example.com', '$2y$10$hashedpassword3', NULL, '{"vi": "Lê Văn C", "en": "Le Van C"}', '0901234563', 'avatars/user3.jpg', 'admin', 'active', NULL, NULL, NOW(), NULL),
('user4@example.com', NULL, 'google124', '{"vi": "Phạm Thị D", "en": "Pham Thi D"}', '0901234564', 'avatars/user4.jpg', 'user', 'inactive', NULL, NULL, NULL, NULL),
('user5@example.com', '$2y$10$hashedpassword5', NULL, '{"vi": "Hoàng Văn E", "en": "Hoang Van E"}', '0901234565', 'avatars/user5.jpg', 'provider', 'banned', 'Violated terms', '2025-12-31 23:59:59', NULL, NULL);


-- Insert dữ liệu mẫu cho preference_types
INSERT INTO preference_types (name, description) VALUES
('{"vi": "Ngân sách thấp", "en": "Budget"}', '{"vi": "Ưu tiên dịch vụ giá rẻ", "en": "Prefers low-cost services"}'),
('{"vi": "Sang trọng", "en": "Luxury"}', '{"vi": "Ưu tiên dịch vụ cao cấp", "en": "Prefers premium services"}'),
('{"vi": "Gia đình", "en": "Family"}', '{"vi": "Phù hợp cho gia đình", "en": "Family-friendly"}'),
('{"vi": "Phiêu lưu", "en": "Adventure"}', '{"vi": "Thích trải nghiệm mạo hiểm", "en": "Loves adventure"}'),
('{"vi": "Văn hóa", "en": "Cultural"}', '{"vi": "Quan tâm đến văn hóa địa phương", "en": "Interested in local culture"}');

-- Bảng service_providers (Nhà cung cấp dịch vụ)


-- Insert dữ liệu mẫu cho service_providers
INSERT INTO service_providers (user_id, name, description, address, phone, email, website, logo, approval_status, license_number, license_file, status) VALUES
(2, '{"vi": "Công ty Du lịch Biển Xanh", "en": "Blue Sea Travel"}', '{"vi": "Cung cấp tour chất lượng", "en": "Quality tours"}', '{"vi": "123 Đường Biển, Bà Rịa", "en": "123 Sea Road, Ba Ria"}', '0901234562', 'provider1@example.com', 'www.bluesea.com', 'logos/provider1.jpg', 'approved', 'LIC001', 'licenses/lic001.pdf', 'active'),
(5, '{"vi": "Khách sạn Sao Biển", "en": "Starfish Hotel"}', '{"vi": "Khách sạn 5 sao", "en": "5-star hotel"}', '{"vi": "456 Đường Biển, Vũng Tàu", "en": "456 Sea Road, Vung Tau"}', '0901234565', 'provider2@example.com', 'www.starfish.com', 'logos/provider2.jpg', 'pending', 'LIC002', 'licenses/lic002.pdf', 'active'),
(2, '{"vi": "Nhà hàng Hải Sản Tươi", "en": "Fresh Seafood Restaurant"}', '{"vi": "Hải sản tươi sống", "en": "Fresh seafood"}', '{"vi": "789 Đường Biển, Bà Rịa", "en": "789 Sea Road, Ba Ria"}', '0901234563', 'provider3@example.com', 'www.seafood.com', 'logos/provider3.jpg', 'approved', 'LIC003', 'licenses/lic003.pdf', 'active'),
(5, '{"vi": "Vận tải Du lịch", "en": "Travel Transport"}', '{"vi": "Xe du lịch chất lượng", "en": "Quality travel vehicles"}', '{"vi": "101 Đường Biển, Vũng Tàu", "en": "101 Sea Road, Vung Tau"}', '0901234564', 'provider4@example.com', 'www.traveltransport.com', 'logos/provider4.jpg', 'rejected', 'LIC004', 'licenses/lic004.pdf', 'inactive'),
(2, '{"vi": "Tour Văn hóa", "en": "Cultural Tour"}', '{"vi": "Tour khám phá văn hóa", "en": "Cultural discovery tours"}', '{"vi": "202 Đường Biển, Bà Rịa", "en": "202 Sea Road, Ba Ria"}', '0901234566', 'provider5@example.com', 'www.culturaltour.com', 'logos/provider5.jpg', 'approved', 'LIC005', 'licenses/lic005.pdf', 'active');



-- Insert dữ liệu mẫu cho provider_ban_history
INSERT INTO provider_ban_history (provider_id, ban_reason, created_at, updated_at) VALUES
(1, 'Không tuân thủ quy định', NOW(), NULL),
(2, 'Vi phạm hợp đồng', NOW(), '2025-06-01 00:00:00'),
(3, 'Khiếu nại từ khách hàng', NOW(), NULL),
(4, 'Giấy phép không hợp lệ', NOW(), NULL),
(5, 'Chất lượng dịch vụ kém', NOW(), '2025-07-01 00:00:00');



-- Insert dữ liệu mẫu cho user_preferences
INSERT INTO user_preferences (user_id, preference_type_id) VALUES
(1, 1),
(1, 3),
(2, 2),
(3, 4),
(4, 5);



-- Insert dữ liệu mẫu cho amenities
INSERT INTO amenities (name, description, icon) VALUES
('{"vi": "Wi-Fi miễn phí", "en": "Free Wi-Fi"}', '{"vi": "Kết nối internet tốc độ cao", "en": "High-speed internet"}', 'wifi'),
('{"vi": "Hồ bơi", "en": "Swimming Pool"}', '{"vi": "Hồ bơi ngoài trời", "en": "Outdoor pool"}', 'pool'),
('{"vi": "Bãi đỗ xe", "en": "Parking"}', '{"vi": "Bãi đỗ xe miễn phí", "en": "Free parking"}', 'parking'),
('{"vi": "Nhà hàng", "en": "Restaurant"}', '{"vi": "Nhà hàng trong khuôn viên", "en": "On-site restaurant"}', 'restaurant'),
('{"vi": "Phòng gym", "en": "Gym"}', '{"vi": "Phòng tập thể dục hiện đại", "en": "Modern gym"}', 'gym');



-- Insert dữ liệu mẫu cho attractions
INSERT INTO attractions (name, type, description, address, latitude, longitude, opening_hours, status) VALUES
('{"vi": "Bãi biển Vũng Tàu", "en": "Vung Tau Beach"}', 'beach', '{"vi": "Bãi biển đẹp", "en": "Beautiful beach"}', '{"vi": "Bãi Sau, Vũng Tàu", "en": "Back Beach, Vung Tau"}', 10.12345678, 106.12345678, '{"vi": "06:00-18:00", "en": "06:00-18:00"}', 'active'),
('{"vi": "Chùa Quan Công", "en": "Quan Cong Temple"}', 'temple', '{"vi": "Chùa cổ kính", "en": "Ancient temple"}', '{"vi": "123 Đường Lê Lợi", "en": "123 Le Loi Road"}', 10.23456789, 106.23456789, '{"vi": "07:00-17:00", "en": "07:00-17:00"}', 'active'),
('{"vi": "Làng chài Phước Hải", "en": "Phuoc Hai Fishing Village"}', 'village', '{"vi": "Làng chài truyền thống", "en": "Traditional fishing village"}', '{"vi": "Phước Hải, Bà Rịa", "en": "Phuoc Hai, Ba Ria"}', 10.34567890, 106.34567890, '{"vi": "Toàn ngày", "en": "All day"}', 'active'),
('{"vi": "Chợ đêm Vũng Tàu", "en": "Vung Tau Night Market"}', 'market', '{"vi": "Chợ đêm sầm uất", "en": "Bustling night market"}', '{"vi": "456 Đường Biển", "en": "456 Sea Road"}', 10.45678901, 106.45678901, '{"vi": "18:00-23:00", "en": "18:00-23:00"}', 'active'),
('{"vi": "Khu di tích Bạch Dinh", "en": "Bach Dinh Historical Site"}', 'historical', '{"vi": "Di tích lịch sử", "en": "Historical site"}', '{"vi": "789 Đường Biển", "en": "789 Sea Road"}', 10.56789012, 106.56789012, '{"vi": "08:00-16:00", "en": "08:00-16:00"}', 'inactive');



-- Insert dữ liệu mẫu cho hotels
INSERT INTO hotels (name, type, provider_id, is_admin_managed, description, address, contact_info, latitude, longitude, price_range, check_in_time, check_out_time, cancellation_policy, status) VALUES
('{"vi": "Khách sạn Biển Xanh", "en": "Blue Sea Hotel"}', 'luxury', 1, FALSE, '{"vi": "Khách sạn 5 sao", "en": "5-star hotel"}', '{"vi": "123 Đường Biển", "en": "123 Sea Road"}', '{"vi": "0901234561", "en": "+84901234561"}', 10.12345678, 106.12345678, '500-1000 USD', '14:00:00', '12:00:00', '{"vi": "Hủy miễn phí 24h", "en": "Free cancel within 24h"}', 'active'),
('{"vi": "Khách sạn Sao Biển", "en": "Starfish Hotel"}', 'resort', 2, TRUE, '{"vi": "Khu nghỉ dưỡng cao cấp", "en": "Luxury resort"}', '{"vi": "456 Đường Biển", "en": "456 Sea Road"}', '{"vi": "0901234562", "en": "+84901234562"}', 10.23456789, 106.23456789, '300-700 USD', '15:00:00', '11:00:00', '{"vi": "Hủy mất phí", "en": "Cancellation fee"}', 'active'),
('{"vi": "Homestay Biển Rạng", "en": "Rang Beach Homestay"}', 'homestay', NULL, FALSE, '{"vi": "Homestay gần biển", "en": "Beachside homestay"}', '{"vi": "789 Đường Biển", "en": "789 Sea Road"}', '{"vi": "0901234563", "en": "+84901234563"}', 10.34567890, 106.34567890, '50-150 USD', '13:00:00', '12:00:00', '{"vi": "Không hoàn tiền", "en": "Non-refunded"}', 'active'),
('{"vi": "Khách sạn Gia đình", "en": "Family Hotel"}', 'family', 3, FALSE, '{"vi": "Phù hợp gia đình", "en": "Family-friendly"}', '{"vi": "101 Đường Biển", "en": "101 Sea Road"}', '{"vi": "0901234564", "en": "+84901234564"}', 10.45678901, 106.45678901, '100-300 USD', '14:00:00', '12:00:00', '{"vi": "Hủy miễn phí 48h", "en": "Free cancel within 48h"}', 'inactive'),
('{"vi": "Villa Biển Ngọc", "en": "Pearl Sea Villa"}', 'villa', 4, TRUE, '{"vi": "Villa sang trọng", "en": "Luxury villa"}', '{"vi": "202 Đường Biển", "en": "202 Sea Road"}', '{"vi": "0901234565", "en": "+84901234565"}', 10.56789012, 106.56789012, '700-1500 USD', '15:00:00', '11:00:00', '{"vi": "Hủy mất phí", "en": "Cancellation fee"}', 'active');



-- Insert dữ liệu mẫu cho rooms
INSERT INTO rooms (hotel_id, name, description, price_per_night, area, capacity, bed_type, status) VALUES
(1, '{"vi": "Phòng Deluxe", "en": "Deluxe Room"}', '{"vi": "Phòng sang trọng", "en": "Luxury room"}', 200.00, 30.50, 2, 'King', 'active'),
(1, '{"vi": "Phòng Suite", "en": "Suite Room"}', '{"vi": "Phòng cao cấp", "en": "Premium room"}', 350.00, 45.75, 4, 'Queen', 'active'),
(2, '{"vi": "Phòng Biển", "en": "Sea View Room"}', '{"vi": "View biển", "en": "Sea view"}', 250.00, 35.00, 3, 'Double', 'active'),
(3, '{"vi": "Phòng Gia đình", "en": "Family Room"}', '{"vi": "Phù hợp gia đình", "en": "Family-friendly"}', 150.00, 40.25, 5, 'Twin', 'inactive'),
(4, '{"vi": "Phòng Tiêu chuẩn", "en": "Standard Room"}', '{"vi": "Phòng cơ bản", "en": "Basic room"}', 100.00, 25.00, 2, 'Single', 'active');

-- Insert dữ liệu mẫu cho restaurants
INSERT INTO restaurants (name, type, price_category, provider_id, is_admin_managed, description, address, contact_info, latitude, longitude, opening_hours, price_range, cancellation_policy, status) VALUES
('{"vi": "Nhà hàng Hải Sản Tươi", "en": "Fresh Seafood Restaurant"}', 'seafood', 'mid_range', 3, FALSE, '{"vi": "Hải sản tươi sống", "en": "Fresh seafood"}', '{"vi": "789 Đường Biển", "en": "789 Sea Road"}', '{"vi": "0901234563", "en": "+84901234563"}', 10.12345678, 106.12345678, '{"vi": "11:00-22:00", "en": "11:00-22:00"}', '20-50 USD', '{"vi": "Hủy miễn phí 24h", "en": "Free cancel within 24h"}', 'active'),
('{"vi": "Quán ăn Việt", "en": "Vietnamese Eatery"}', 'vietnamese', 'budget', NULL, TRUE, '{"vi": "Ẩm thực Việt Nam", "en": "Vietnamese cuisine"}', '{"vi": "123 Đường Lê Lợi", "en": "123 Le Loi Road"}', '{"vi": "0901234564", "en": "+84901234564"}', 10.23456789, 106.23456789, '{"vi": "07:00-21:00", "en": "07:00-21:00"}', '10-30 USD', '{"vi": "Không hoàn tiền", "en": "Non-refunded"}', 'active'),
('{"vi": "Nhà hàng Á Âu", "en": "Asian-Western Restaurant"}', 'fusion', 'fine_dining', 1, FALSE, '{"vi": "Ẩm thực kết hợp", "en": "Fusion cuisine"}', '{"vi": "456 Đường Biển", "en": "456 Sea Road"}', '{"vi": "0901234565", "en": "+84901234565"}', 10.34567890, 106.34567890, '{"vi": "12:00-23:00", "en": "12:00-23:00"}', '50-100 USD', '{"vi": "Hủy mất phí", "en": "Cancellation fee"}', 'active'),
('{"vi": "Quán chay Tâm An", "en": "Tam An Vegetarian Eatery"}', 'vegetarian', 'budget', NULL, TRUE, '{"vi": "Ẩm thực chay", "en": "Vegetarian cuisine"}', '{"vi": "101 Đường Biển", "en": "101 Sea Road"}', '{"vi": "0901234566", "en": "+84901234566"}', 10.45678901, 106.45678901, '{"vi": "06:00-20:00", "en": "06:00-20:00"}', '5-20 USD', '{"vi": "Hủy miễn phí 48h", "en": "Free cancel within 48h"}', 'inactive'),
('{"vi": "Nhà hàng Buffet Biển", "en": "Sea Buffet Restaurant"}', 'buffet', 'luxury', 2, FALSE, '{"vi": "Buffet đa dạng", "en": "Diverse buffet"}', '{"vi": "202 Đường Biển", "en": "202 Sea Road"}', '{"vi": "0901234567", "en": "+84901234567"}', 10.56789012, 106.56789012, '{"vi": "11:00-22:00", "en": "11:00-22:00"}', '50-150 USD', '{"vi": "Hủy mất phí", "en": "Cancellation fee"}', 'active');


-- Insert dữ liệu mẫu cho dishes
INSERT INTO dishes (name, restaurant_id, description, price, price_range, dish_type, status) VALUES
('{"vi": "Tôm hùm nướng", "en": "Grilled Lobster"}', 1, '{"vi": "Tôm hùm tươi", "en": "Fresh lobster"}', 50.00, '40-60 USD', 'seafood', 'active'),
('{"vi": "Phở bò", "en": "Beef Pho"}', 2, '{"vi": "Phở truyền thống", "en": "Traditional pho"}', 10.00, '8-12 USD', 'local', 'active'),
('{"vi": "Sushi tổng hợp", "en": "Assorted Sushi"}', 3, '{"vi": "Sushi tươi ngon", "en": "Fresh sushi"}', 30.00, '25-35 USD', 'other', 'active'),
('{"vi": "Nước ép trái cây", "en": "Fruit Juice"}', 4, '{"vi": "Nước ép tự nhiên", "en": "Natural juice"}', 5.00, '3-7 USD', 'drink', 'active'),
('{"vi": "Cơm chiên hải sản", "en": "Seafood Fried Rice"}', 5, '{"vi": "Cơm chiên thơm ngon", "en": "Tasty fried rice"}', 20.00, '15-25 USD', 'seafood', 'inactive');



-- Insert dữ liệu mẫu cho tours
INSERT INTO tours (name, type, provider_id, is_admin_managed, created_by, description, contact_info, duration_days, price, max_people, status) VALUES
('{"vi": "Tour Biển Vũng Tàu", "en": "Vung Tau Beach Tour"}', 'beach', 1, FALSE, 2, '{"vi": "Khám phá bãi biển", "en": "Explore the beach"}', '{"vi": "0901234561", "en": "+84901234561"}', 2, 150.00, 20, 'active'),
('{"vi": "Tour Văn hóa Bà Rịa", "en": "Ba Ria Cultural Tour"}', 'cultural', 5, TRUE, 3, '{"vi": "Khám phá văn hóa", "en": "Discover culture"}', '{"vi": "0901234562", "en": "+84901234562"}', 3, 200.00, 15, 'active'),
('{"vi": "Tour Phiêu lưu Núi Dinh", "en": "Nui Dinh Adventure Tour"}', 'adventure', NULL, FALSE, 1, '{"vi": "Leo núi mạo hiểm", "en": "Mountain hiking"}', '{"vi": "0901234563", "en": "+84901234563"}', 1, 100.00, 10, 'active'),
('{"vi": "Tour Gia đình", "en": "Family Tour"}', 'family', 3, FALSE, 2, '{"vi": "Phù hợp gia đình", "en": "Family-friendly"}', '{"vi": "0901234564", "en": "+84901234564"}', 2, 120.00, 25, 'inactive'),
('{"vi": "Tour Lãng mạn", "en": "Romantic Tour"}', 'romantic', 4, TRUE, 3, '{"vi": "Tour cho cặp đôi", "en": "Couple tour"}', '{"vi": "0901234565", "en": "+84901234565"}', 1, 180.00, 2, 'active');



-- Insert dữ liệu mẫu cho tour_details
INSERT INTO tour_details (tour_id, day_number, attraction_id, description) VALUES
(1, 1, 1, '{"vi": "Tham quan bãi biển Vũng Tàu", "en": "Visit Vung Tau Beach"}'),
(1, 2, 4, '{"vi": "Khám phá chợ đêm", "en": "Explore night market"}'),
(2, 1, 2, '{"vi": "Tham quan chùa Quan Công", "en": "Visit Quan Cong Temple"}'),
(3, 1, 3, '{"vi": "Leo núi Dinh", "en": "Hike Nui Dinh"}'),
(4, 1, 1, '{"vi": "Thư giãn tại bãi biển", "en": "Relax at the beach"}');



-- Insert dữ liệu mẫu cho transports
INSERT INTO transports (name, type, provider_id, is_admin_managed, contact_info, capacity, price_per_day, status) VALUES
('{"vi": "Xe du lịch 16 chỗ", "en": "16-seat Tour Bus"}', 'car',  4, FALSE, '{"vi": "0901234564", "en": "+84901234564"}', 16, 100.00, 'available'),
('{"vi": "Xe máy Honda", "en": "Honda Motorbike"}', 'motorbike',  NULL, TRUE, '{"vi": "0901234565", "en": "+84901234565"}', 2, 10.00, 'available'),
('{"vi": "Thuyền du lịch", "en": "Tour Boat"}', 'boat',  1, FALSE, '{"vi": "0901234566", "en": "+84901234566"}', 20, 200.00, 'booked'),
('{"vi": "Xe đạp đôi", "en": "Tandem Bicycle"}', 'bicycle', 2, FALSE, '{"vi": "0901234567", "en": "+84901234567"}', 2, 5.00, 'available'),
('{"vi": "Xe hơi sang trọng", "en": "Luxury Car"}', 'car',  3, TRUE, '{"vi": "0901234568", "en": "+84901234568"}', 4, 150.00, 'available');



-- Insert dữ liệu mẫu cho events
INSERT INTO events (title, description, start_date, end_date, location, attraction_id, status) VALUES
('{"vi": "Lễ hội Biển Vũng Tàu", "en": "Vung Tau Beach Festival"}', '{"vi": "Lễ hội lớn", "en": "Grand festival"}', '2025-06-01 08:00:00', '2025-06-02 22:00:00', '{"vi": "Bãi Sau", "en": "Back Beach"}', 1, 'upcoming'),
('{"vi": "Triển lãm Văn hóa", "en": "Cultural Exhibition"}', '{"vi": "Triển lãm văn hóa", "en": "Cultural exhibition"}', '2025-07-01 09:00:00', '2025-07-03 17:00:00', '{"vi": "Chùa Quan Công", "en": "Quan Cong Temple"}', 2, 'upcoming'),
('{"vi": "Chợ đêm ẩm thực", "en": "Night Market Food Festival"}', '{"vi": "Ẩm thực đường phố", "en": "Street food"}', '2025-08-01 18:00:00', '2025-08-01 23:00:00', '{"vi": "Chợ đêm Vũng Tàu", "en": "Vung Tau Night Market"}', 4, 'upcoming'),
('{"vi": "Hội thi câu cá", "en": "Fishing Competition"}', '{"vi": "Thi câu cá", "en": "Fishing contest"}', '2025-05-20 06:00:00', '2025-05-20 12:00:00', '{"vi": "Làng chài Phước Hải", "en": "Phuoc Hai Fishing Village"}', 3, 'completed'),
('{"vi": "Triển lãm lịch sử", "en": "Historical Exhibition"}', '{"vi": "Lịch sử địa phương", "en": "Local history"}', '2025-09-01 08:00:00', '2025-09-03 16:00:00', '{"vi": "Bạch Dinh", "en": "Bach Dinh"}', 5, 'upcoming');



-- Insert dữ liệu mẫu cho bookings
INSERT INTO bookings (user_id, service_type, service_id, room_id, booking_date, start_date, end_date, number_of_people, special_requests, booking_code, status) VALUES
(1, 'hotel', 1, 1, '2025-05-16', '2025-05-20', '2025-05-25', 2, 'Extra pillows', 'BOOK001', 'pending'),
(2, 'tour', 1, NULL, '2025-05-17', '2025-05-20', '2025-05-22', 4, 'Vegetarian meals', 'BOOK002', 'confirmed'),
(3, 'transport', 1, NULL, '2025-05-18', '2025-05-20', '2025-05-20', 10, 'Child seats', 'BOOK003', 'pending'),
(4, 'restaurant', 1, NULL, '2025-05-19', '2025-05-20', NULL, 6, 'Outdoor seating', 'BOOK004', 'confirmed'),
(5, 'hotel', 2, 2, '2025-05-20', '2025-05-21', '2025-05-23', 3, 'Late check-in', 'BOOK005', 'cancelled');




-- Insert dữ liệu mẫu cho amenity_entity
INSERT INTO amenity_entity (amenity_id, entity_type, entity_id) VALUES
(1, 'hotel', 1),
(2, 'hotel', 2),
(3, 'room', 1),
(4, 'restaurant', 1),
(5, 'tour', 1);



-- Insert dữ liệu mẫu cho images
INSERT INTO images (entity_type, entity_id, url, caption, is_featured) VALUES
('hotel', 1, 'images/hotel1.jpg', '{"vi": "Toàn cảnh khách sạn", "en": "Hotel overview"}', TRUE),
('room', 1, 'images/room1.jpg', '{"vi": "Phòng Deluxe", "en": "Deluxe room"}', FALSE),
('restaurant', 1, 'images/restaurant1.jpg', '{"vi": "Khu vực ăn uống", "en": "Dining area"}', TRUE),
('tour', 1, 'images/tour1.jpg', '{"vi": "Bãi biển tour", "en": "Beach tour"}', TRUE),
('attraction', 1, 'images/attraction1.jpg', '{"vi": "Bãi biển Vũng Tàu", "en": "Vung Tau Beach"}', FALSE);



-- Insert dữ liệu mẫu cho reviews
INSERT INTO reviews (user_id, entity_type, entity_id, rating, comment, status) VALUES
(1, 'hotel', 1, 5, 'Great service!', 'approved'),
(2, 'room', 1, 4, 'Comfortable but small', 'pending'),
(3, 'restaurant', 1, 5, 'Delicious food', 'approved'),
(4, 'tour', 1, 3, 'Good but crowded', 'rejected'),
(5, 'attraction', 1, 4, 'Beautiful beach', 'approved');



-- Insert dữ liệu mẫu cho favorites
INSERT INTO favorites (user_id, entity_type, entity_id) VALUES
(1, 'hotel', 1),
(2, 'restaurant', 1),
(3, 'tour', 1),
(4, 'attraction', 1),
(5, 'dish', 1);



-- Insert dữ liệu mẫu cho itineraries
INSERT INTO itineraries (user_id, title, start_date, end_date, share_token) VALUES
(1, 'Chuyến đi Vũng Tàu', '2025-05-20', '2025-05-25', 'TOKEN001'),
(2, 'Khám phá Bà Rịa', '2025-06-01', '2025-06-03', 'TOKEN002'),
(3, 'Nghỉ dưỡng gia đình', '2025-07-01', '2025-07-05', NULL),
(4, 'Tour phiêu lưu', '2025-08-01', '2025-08-02', 'TOKEN004'),
(5, 'Chuyến đi lãng mạn', '2025-09-01', '2025-09-03', 'TOKEN005');



-- Insert dữ liệu mẫu cho itinerary_details
INSERT INTO itinerary_details (itinerary_id, entity_type, entity_id, visit_date, notes, estimated_cost) VALUES
(1, 'hotel', 1, '2025-05-20', 'Stay at Blue Sea Hotel', 200.00),
(1, 'restaurant', 1, '2025-05-21', 'Dinner at seafood restaurant', 50.00),
(2, 'tour', 1, '2025-06-01', 'Beach tour', 150.00),
(3, 'attraction', 1, '2025-07-01', 'Visit Vung Tau Beach', 0.00),
(4, 'transport', 1, '2025-08-01', 'Book tour bus', 100.00);


-- Insert dữ liệu mẫu cho posts
INSERT INTO posts (title, content, author_id, topic, attraction_id, status, rejection_reason, tags, short_description, views) VALUES
('Hướng dẫn du lịch Vũng Tàu', 'Khám phá bãi biển đẹp...', 1, 'travel_tips',  1, 'published', NULL, 'beach,travel', 'Hướng dẫn chi tiết', 100),
('Ẩm thực Bà Rịa', 'Thưởng thức hải sản...', 2, 'food', NULL, 'draft', NULL, 'food,seafood', 'Đánh giá món ăn', 50),
('Lễ hội biển 2025', 'Sự kiện lớn tại Vũng Tàu...', 3, 'event', 1, 'published', NULL, 'event,beach', 'Thông tin lễ hội', 200),
('Chùa Quan Công', 'Khám phá văn hóa tâm linh...', 4, 'culture',  2, 'rejected', 'Content unclear', 'culture,temple', 'Hướng dẫn tham quan', 10),
('Chợ đêm Vũng Tàu', 'Trải nghiệm mua sắm...', 5, 'activity', 4, 'published', NULL, 'market,shopping', 'Đánh giá chợ đêm', 150);


-- Insert dữ liệu mẫu cho post_comments
INSERT INTO post_comments (post_id, user_id, parent_id, content, status, rejection_reason) VALUES
(1, 1, NULL, 'Bài viết rất hữu ích!', 'approved', NULL),
(1, 2, 1, 'Cảm ơn bạn!', 'pending', NULL),
(2, 3, NULL, 'Hải sản ngon tuyệt!', 'approved', NULL),
(3, 4, NULL, 'Hóng sự kiện này!', 'rejected', 'Inappropriate content'),
(4, 5, NULL, 'Chùa rất đẹp!', 'approved', NULL);


-- Insert dữ liệu mẫu cho notifications
INSERT INTO notifications (user_id, type, message, is_read, related_entity_type, related_entity_id, booking_id) VALUES
(1, 'booking_confirmation', 'Booking BOOK001 confirmed', FALSE, 'hotel', 1, 1),
(2, 'booking_status_change', 'Booking BOOK002 updated', TRUE, 'tour', 1, 2),
(3, 'system', 'System maintenance scheduled', FALSE, NULL, NULL, NULL),
(4, 'other', 'New restaurant added', FALSE, 'restaurant', 1, NULL),
(5, 'booking_confirmation', 'Booking BOOK005 cancelled', TRUE, 'hotel', 2, 5);

-- Insert dữ liệu mẫu cho contacts
INSERT INTO contacts (name, email, message, status) VALUES
('Nguyễn Văn A', 'user1@example.com', 'Hỏi về tour du lịch', 'new'),
('Trần Thị B', 'user2@example.com', 'Phản hồi về khách sạn', 'read'),
('Lê Văn C', 'user3@example.com', 'Đề xuất cải thiện dịch vụ', 'replied'),
('Phạm Thị D', 'user4@example.com', 'Yêu cầu hỗ trợ đặt phòng', 'new'),
('Hoàng Văn E', 'user5@example.com', 'Khiếu nại dịch vụ', 'read');

