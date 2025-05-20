<?php
// app/Http/Controllers/Auth/ForgotPasswordController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\ResetPassword;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Hiển thị form quên mật khẩu
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    
    /**
     * Xử lý gửi email đặt lại mật khẩu
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
        ]);
        
        // Xóa tất cả token cũ của email này
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();
            
        // Tạo token mới
        $token = Str::random(64);
        
        // Lưu thông tin reset token vào database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        
        // Tìm user để gửi email
        $user = User::where('email', $request->email)->first();
        
        // Gửi email đặt lại mật khẩu
        Mail::to($request->email)->send(new ResetPassword($token, $user));
        
        return back()->with('status', 'Chúng tôi đã gửi liên kết đặt lại mật khẩu qua email của bạn!');
    }
}