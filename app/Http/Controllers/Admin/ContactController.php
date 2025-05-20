<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Notification;
use App\Mail\ContactReply;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->get();
        return view('admin.contacts.index', compact('contacts'));
    }
    
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Cập nhật trạng thái nếu là 'new'
        if ($contact->status == 'new') {
            $contact->status = 'read';
            $contact->save();
        }
        
        return view('admin.contacts.show', compact('contact'));
    }
    
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string'
        ]);
        
        $contact = Contact::findOrFail($id);
        $replyMessage = $request->reply_message;
        
        // Gửi email
        try {
            Mail::to($contact->email)->send(new ContactReply($contact, $replyMessage));
            
            // Cập nhật trạng thái
            $contact->status = 'replied';
            $contact->save();
            
            // Tạo thông báo cho người dùng (nếu là user đã đăng ký)
            $user = User::where('email', $contact->email)->first();
            if ($user) {
                Notification::create([
                    'user_id' => $user->user_id,
                    'message' => 'Phản hồi cho liên hệ của bạn đã được xử lý.',
                    'is_read' => false
                ]);
            }
            
            return redirect()->route('admin.contacts.index')->with('success', 'Đã trả lời liên hệ thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }
    }
    
    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')->with('success', 'Đã xóa liên hệ thành công!');
    }
}