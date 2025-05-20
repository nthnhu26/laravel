<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị thông tin chi tiết người dùng
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Cập nhật trạng thái người dùng
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,banned',
            'ban_reason' => 'required_if:status,banned',
            'banned_until' => 'nullable|date|after:now'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->status = $request->status;
        
        if ($request->status == 'banned') {
            $user->ban_reason = $request->ban_reason;
            $user->banned_until = $request->banned_until;
        } else {
            $user->ban_reason = null;
            $user->banned_until = null;
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật trạng thái người dùng thành công!');
    }
    
    /**
     * Kích hoạt tài khoản người dùng
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->ban_reason = null;
        $user->banned_until = null;
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kích hoạt tài khoản thành công!');
    }
    
    /**
     * Vô hiệu hóa tài khoản người dùng
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'inactive';
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Vô hiệu hóa tài khoản thành công!');
    }
    
    /**
     * Hiển thị form khóa tài khoản
     */
    public function showBanForm($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.ban', compact('user'));
    }
    
    /**
     * Xử lý khóa tài khoản
     */
    public function ban(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'ban_reason' => 'required|string|max:500',
            'banned_until' => 'nullable|date|after:now'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user->status = 'banned';
        $user->ban_reason = $request->ban_reason;
        $user->banned_until = $request->banned_until;
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Khóa tài khoản thành công!');
    }
}