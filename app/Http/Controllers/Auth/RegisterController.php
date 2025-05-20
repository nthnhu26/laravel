<?php
// app/Http/Controllers/Auth/RegisterController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'name' => 'required|string|max:100',
            ]);
    
            $verificationToken = Str::random(64);
            
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'status' => 'inactive',
                'remember_token' => $verificationToken,
            ]);
            Log::info('Register attempt', $request->all());
            Mail::to($user->email)->send(new VerifyEmail($user));
    
            return redirect()->route('verification.notice')
                ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Đăng ký thất bại, vui lòng thử lại.']);
        }
    }
    
    public function verify($token)
    {
        $user = User::where('remember_token', $token)->first();
        
        if (!$user) {
            return redirect('login')->with('error', 'Liên kết xác thực không hợp lệ!');
        }
        
        $user->email_verified_at = now();
        $user->status = 'active';
        $user->remember_token = null;
        $user->save();
        
        Auth::login($user);
        
        return redirect()->route('home')
            ->with('success', 'Tài khoản của bạn đã được xác thực thành công!');
    }
    
    public function showVerificationNotice()
    {
        return view('auth.verify');
    }
    
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        
        $user = User::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();
            
        if (!$user) {
            return back()->with('error', 'Email không tồn tại hoặc đã được xác thực!');
        }
        
        // Tạo token mới
        $verificationToken = Str::random(64);
        $user->remember_token = $verificationToken;
        $user->save();
        
        // Gửi lại email xác thực
        Mail::to($user->email)->send(new VerifyEmail($user));
        
        return back()->with('success', 'Email xác thực đã được gửi lại!');
    }
}