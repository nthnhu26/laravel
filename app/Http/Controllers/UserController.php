<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function profile()
    {
        return view('users.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'avatar_url' => 'nullable|url',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->full_name = $request->full_name;

        if ($request->filled('avatar_url')) {
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->avatar_url;
        } elseif ($request->hasFile('avatar_file')) {
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar_file')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (is_null($user->password)) {
            // Tài khoản Google: Thêm mật khẩu
            $request->validate([
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('profile')->with('success', 'Thêm mật khẩu thành công! Bạn có thể đăng nhập bằng email và mật khẩu.');
        } else {
            // Tài khoản đã có mật khẩu: Đổi mật khẩu
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('profile')->with('success', 'Đổi mật khẩu thành công!');
        }
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:XÓA TÀI KHOẢN',
        ]);

        $user = Auth::user();

        if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Tài khoản của bạn đã được xóa thành công.');
    }
}
