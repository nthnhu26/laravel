<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\DB;

class ServiceProviderController extends Controller
{
    /**
     * Hiển thị danh sách nhà cung cấp
     */
    public function index()
    {
        $serviceProviders = ServiceProvider::with('user')
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('admin.service-providers.index', compact('serviceProviders'));
    }

    /**
     * Hiển thị danh sách phê duyệt nhà cung cấp
     */
    public function approvalList()
    {
        $pendingProviders = ServiceProvider::with('user')
                            ->where('approval_status', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('admin.service-providers.approval', compact('pendingProviders'));
    }

    /**
     * Hiển thị chi tiết nhà cung cấp
     */
    public function show($id)
    {
        $provider = ServiceProvider::with('user')->findOrFail($id);
        
        return view('admin.service-providers.show', compact('provider'));
    }

    /**
     * Phê duyệt nhà cung cấp
     */
    public function approve(Request $request, $id)
    {
        $provider = ServiceProvider::findOrFail($id);
        $provider->approval_status = 'approved';
        $provider->save();
        
        return redirect()->route('admin.service-providers.approval')
                         ->with('success', 'Nhà cung cấp đã được phê duyệt thành công.');
    }

    /**
     * Từ chối nhà cung cấp
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:255',
        ]);
        
        $provider = ServiceProvider::findOrFail($id);
        $provider->approval_status = 'rejected';
        $provider->save();
        
        // Có thể lưu lý do từ chối vào một bảng riêng hoặc gửi email thông báo

        return redirect()->route('admin.service-providers.approval')
                         ->with('success', 'Đã từ chối nhà cung cấp.');
    }

    /**
     * Thay đổi trạng thái hoạt động của nhà cung cấp
     */
    public function toggleStatus($id)
    {
        $provider = ServiceProvider::findOrFail($id);
        $provider->status = ($provider->status == 'active') ? 'inactive' : 'active';
        $provider->save();
        
        return redirect()->route('admin.service-providers.index')
                         ->with('success', 'Đã thay đổi trạng thái nhà cung cấp.');
    }
}