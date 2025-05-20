<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::get('/change-language/{locale}', function ($locale) {
    if (in_array($locale, ['vi', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('change.language');



// Đăng ký tài khoản
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
// Đăng nhập bằng Google
Route::get('redirect/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])->name('auth.google.callback');
// Đăng nhập thủ công
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
// Quên mật khẩu
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Đặt lại mật khẩu
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/update', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
// Xác thực tài khoản qua email
Route::get('/email/verify', [App\Http\Controllers\Auth\RegisterController::class, 'showVerificationNotice'])->name('verification.notice');
Route::get('/verify-email/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'verify'])->name('verification.verify');
// Gửi lại email xác thực
Route::post('/email/verification-notification', [App\Http\Controllers\Auth\RegisterController::class, 'resendVerificationEmail'])->name('verification.send');
// Đăng xuất
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\HotelController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\ReviewController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// Hotels
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

// Attractions


// Tours
Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/boat', [TourController::class, 'boat'])->name('tours.boat');
Route::get('/tours/cultural', [TourController::class, 'cultural'])->name('tours.cultural');

// Events


// AI Assistant
Route::get('/ai-assistant', [AIAssistantController::class, 'index'])->name('ai_assistant');



// Bookings
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store')->middleware('auth');



Route::get('/attractions', [AttractionController::class, 'index'])->name('attractions.index');
Route::get('/attractions/{id}', [AttractionController::class, 'show'])->name('attractions.show');
// Route::post('/attractions/review', [AttractionController::class, 'storeReview'])->name('reviews.store')->middleware('auth');

Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');

Route::get('/transports', [TransportController::class, 'index'])->name('transports.index');
Route::get('/transports/{id}', [TransportController::class, 'show'])->name('transports.show');

Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/{id}', [TourController::class, 'show'])->name('tours.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/comments', [App\Http\Controllers\PostCommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::get('/posts/type/{type}', [PostController::class, 'byType'])->name('posts.byType');
Route::get('/posts/tag/{tag}', [PostController::class, 'byTag'])->name('posts.byTag');
// Thêm route này vào routes/web.php
Route::delete('/comments/{id}', [App\Http\Controllers\PostCommentController::class, 'destroy'])->name('comments.destroy');
// Thêm routes này vào routes/web.php


// Route chung cho Review
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::post('/favorites/toggle', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

use App\Http\Controllers\UserController;

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/favorites', [UserController::class, 'favorites'])->name('user.favorites');
    Route::get('/user/itineraries', [UserController::class, 'itineraries'])->name('user.itineraries');
    Route::get('/user/bookings', [UserController::class, 'bookings'])->name('user.bookings');
    Route::get('/user/reviews', [UserController::class, 'reviews'])->name('user.reviews');
    // Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/update', [UserController::class, 'updatePassword'])->name('password.update');
    Route::delete('/account/delete', [UserController::class, 'deleteAccount'])->name('account.delete');
});

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\SearchHistoryController;
use App\Http\Controllers\Admin\ServiceProviderController as AdminProviderController;
use App\Http\Controllers\Admin\PlaceController as AdminPlaceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Routes cho trang quản trị
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('places', AdminPlaceController::class);
    Route::post('places/remove-image', [AdminPlaceController::class, 'removeImage'])->name('places.remove-image');
    Route::post('places/set-featured-image', [AdminPlaceController::class, 'setFeaturedImage'])->name('places.set-featured-image');
    // Routes cho nguời dùng
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/activate', [App\Http\Controllers\Admin\UserController::class, 'activate'])->name('users.activate');
    Route::get('/users/{id}/deactivate', [App\Http\Controllers\Admin\UserController::class, 'deactivate'])->name('users.deactivate');
    Route::get('/users/{id}/ban', [App\Http\Controllers\Admin\UserController::class, 'showBanForm'])->name('users.ban.form');
    Route::post('/users/{id}/ban', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('users.ban');
    Route::put('/users/{id}/status', [App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.update-status');
    // Routes cho danh mục
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Routes cho tiện ích
    Route::get('/amenities', [AmenityController::class, 'index'])->name('amenities.index');
    Route::get('/amenities/create', [AmenityController::class, 'create'])->name('amenities.create');
    Route::post('/amenities', [AmenityController::class, 'store'])->name('amenities.store');
    Route::get('/amenities/{id}/edit', [AmenityController::class, 'edit'])->name('amenities.edit');
    Route::put('/amenities/{id}', [AmenityController::class, 'update'])->name('amenities.update');
    Route::delete('/amenities/{id}', [AmenityController::class, 'destroy'])->name('amenities.destroy');

    // Route cho quản lý nhà cung cấp dịch vụ
    Route::get('/service-providers', [AdminProviderController::class, 'index'])->name('service-providers.index');
    Route::get('/service-providers/approval', [AdminProviderController::class, 'approvalList'])->name('service-providers.approval');
    Route::get('/service-providers/{id}', [AdminProviderController::class, 'show'])->name('service-providers.show');
    Route::get('/service-providers/{id}/approve', [AdminProviderController::class, 'approve'])->name('service-providers.approve');
    Route::put('/service-providers/{id}/reject', [AdminProviderController::class, 'reject'])->name('service-providers.reject');
    Route::put('/service-providers/{id}/toggle-status', [AdminProviderController::class, 'toggleStatus'])->name('service-providers.toggle-status');

    // Quản lý lịch sử tìm kiếm
    // Route::get('/search-history', 'App\Http\Controllers\Admin\SearchHistoryController@index')->name('search-history.index');
    // Route::delete('/search-history/{search}', 'App\Http\Controllers\Admin\SearchHistoryController@destroy')->name('search-history.destroy');
    Route::get('/search-history', [SearchHistoryController::class, 'index'])->name('search-history.index');
    Route::delete('/search-history/{id}', [SearchHistoryController::class, 'destroy'])->name('search-history.destroy');
    Route::get('/search-history/export', [SearchHistoryController::class, 'export'])->name('search-history.export');

    // Quản lý liên hệ
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::post('/contacts/{id}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('/contacts/{id}', [AdminContactController::class, 'delete'])->name('contacts.delete');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/tours', [AdminTourController::class, 'index'])->name('tours.index');
    Route::get('/tours/create', [AdminTourController::class, 'create'])->name('tours.create');
    Route::post('/tours', [AdminTourController::class, 'store'])->name('tours.store');
    Route::get('/tours/{id}/edit', [AdminTourController::class, 'edit'])->name('tours.edit');
    Route::put('/tours/{id}', [AdminTourController::class, 'update'])->name('tours.update');
    Route::delete('/tours/{id}', [AdminTourController::class, 'destroy'])->name('tours.destroy');
    Route::get('/tours/bookings', [AdminTourController::class, 'bookings'])->name('tours.bookings');
    Route::put('/tours/bookings/{id}/status', [AdminTourController::class, 'updateBookingStatus'])->name('tours.booking-status');


    Route::resource('events', AdminEventController::class);
    Route::post('/events/{id}/update-status', [AdminEventController::class, 'updateStatus'])->name('events.update-status');
    Route::get('/events-update-all-statuses', [AdminEventController::class, 'updateAllStatuses'])->name('events.update-all-statuses');
    Route::get('/events-export', [AdminEventController::class, 'export'])->name('events.export');
    Route::get('/events-statistics', [AdminEventController::class, 'statistics'])->name('events.statistics');

    Route::resource('hotels', AdminHotelController::class);
    Route::resource('restaurants', App\Http\Controllers\Admin\RestaurantController::class);
    Route::resource('attractions', App\Http\Controllers\Admin\AttractionController::class);
    Route::resource('transports', App\Http\Controllers\Admin\TransportController::class);

    Route::resource('tours', App\Http\Controllers\Admin\TourController::class);

    Route::resource('amenities', App\Http\Controllers\Admin\AmenityController::class);
    Route::resource('images', App\Http\Controllers\Admin\ImageController::class);

    Route::resource('rooms', App\Http\Controllers\Admin\RoomController::class)->only(['create', 'store']);

    Route::post('admin/upload-image', [App\Http\Controllers\Admin\UploadController::class, 'uploadImage'])->name('upload.image');
});


use App\Http\Controllers\ChatbotController;

// Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot');

// routes/web.php
use App\Http\Controllers\Admin\ChatbotTrainingController;


// // Route cho API chatbot (người dùng)
// Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');

// Route cho quản lý chatbot (admin)
// Route::middleware(['auth', 'role:admin'])->prefix('admin/chatbot')->group(function () {
//     // Trang chính: Quản lý mẫu câu hỏi và kiểm tra nhận diện
//     Route::get('/train', [ChatbotTrainingController::class, 'index'])->name('admin.chatbot.train');
//     Route::get('/intents/{intent}/edit', [ChatbotTrainingController::class, 'edit'])->name('admin.chatbot.intents.edit');
//     Route::put('/intents/{intent}', [ChatbotTrainingController::class, 'update'])->name('admin.chatbot.intents.update');
//     Route::post('/test', [ChatbotTrainingController::class, 'test'])->name('admin.chatbot.test');
//     Route::post('/intents/suggest', [ChatbotTrainingController::class, 'suggest'])->name('admin.chatbot.intents.suggest');

//     // Quản lý hội thoại
//     Route::get('/conversations', [ChatbotTrainingController::class, 'conversations'])->name('admin.chatbot.conversations');
//     Route::get('/conversations/{conversation}', [ChatbotTrainingController::class, 'showConversation'])->name('admin.chatbot.conversations.show');
//     Route::delete('/conversations/{conversation}', [ChatbotTrainingController::class, 'destroyConversation'])->name('admin.chatbot.conversations.destroy');

//     // Phân tích hiệu suất
//     Route::get('/analytics', [ChatbotTrainingController::class, 'analytics'])->name('admin.chatbot.analytics');

//     // Cấu hình chatbot
//     Route::get('/config', [ChatbotTrainingController::class, 'config'])->name('admin.chatbot.config');
//     Route::post('/config', [ChatbotTrainingController::class, 'updateConfig'])->name('admin.chatbot.config.update');
// });

// Route::get('/search', function () {
//     return view('search');
// })->name('search');

// Route::get('/chatbot', function () {
//     return view('chatbot');
// })->name('chatbot');

// Route::get('/admin/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');

Route::post('/chat', [ChatbotController::class, 'chat']);

// Protected chatbot routes (require authentication)
// Route::middleware(['auth'])->group(function () {
//     Route::get('/chat/history', [ChatbotController::class, 'getChatHistory']);
// });
