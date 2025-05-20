<?php
// app/Http/Controllers/Auth/ResetPasswordController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Hiển thị form đặt lại mật khẩu
     */
    public function showResetForm($token)
    {
        // Kiểm tra token có tồn tại không
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();
            
        if (!$passwordReset) {
            return redirect()->route('password.request')
                ->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn!');
        }
        
        return view('auth.reset-passwords', ['token' => $token, 'email' => $passwordReset->email]);
    }
    
    /**
     * Xử lý đặt lại mật khẩu
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);
        
        // Kiểm tra token có hợp lệ và chưa hết hạn (token có hiệu lực trong 60 phút)
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();
            
        if (!$passwordReset || Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn!']);
        }
        
        // Cập nhật mật khẩu mới
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Xóa token đặt lại mật khẩu sau khi đã sử dụng
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();
            
        return redirect()->route('login')
            ->with('status', 'Mật khẩu của bạn đã được đặt lại thành công!');
    }
}