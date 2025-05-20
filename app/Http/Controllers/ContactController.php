<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Hiển thị trang liên hệ
     */
    public function index()
    {
        return view('frontend.contact');
    }

    /**
     * Lưu thông tin liên hệ
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'message' => 'required|string',
        ]);

        try {
            // Lưu vào cơ sở dữ liệu
            Contact::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'status' => 'new',
            ]);

            // Ghi log (tùy chọn)
            Log::info('Liên hệ mới được gửi', ['email' => $validated['email'], 'name' => $validated['name']]);

            // Chuyển hướng với thông báo thành công
            return redirect()->route('contact.index')->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
        } catch (\Exception $e) {
            // Ghi log lỗi
            Log::error('Lỗi khi lưu liên hệ', ['error' => $e->getMessage()]);

            // Chuyển hướng với thông báo lỗi
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra. Vui lòng thử lại sau.']);
        }
    }
}