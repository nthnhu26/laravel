<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class GoogleController extends Controller
{
    /**
     * Chuyển hướng người dùng đến trang đăng nhập Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Nếu user đã đăng ký bằng email thường, cập nhật google_id
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            } else {
                // Nếu user chưa tồn tại, tạo mới
                $user = User::create([
                    'google_id' => $googleUser->id,
                    'email' => $googleUser->email,
                    'full_name' => $googleUser->name,
                    'avatar' => $googleUser->avatar,
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            // Cập nhật avatar nếu thay đổi
            if ($user->avatar !== $googleUser->getAvatar()) {
                $user->update(['avatar' => $googleUser->getAvatar()]);
            }

            Auth::login($user);

            request()->session()->regenerate();

            return $this->redirectAfterLogin($user);
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Đăng nhập thất bại. Vui lòng thử lại.');
        }
    }

    public function redirectAfterLogin($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        }
        return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
    }
}
