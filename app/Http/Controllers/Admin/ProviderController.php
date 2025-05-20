<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    /**
     * Hiển thị danh sách nhà cung cấp
     */
    public function index()
    {
        $providers = ServiceProvider::with('user')->get();
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Hiển thị form tạo mới nhà cung cấp
     */
    public function create()
    {
        $users = User::all();
        $approvalStatuses = ServiceProvider::getApprovalStatuses();
        $statuses = ServiceProvider::getStatuses();
        return view('admin.providers.create', compact('users', 'approvalStatuses', 'statuses'));
    }

    /**
     * Lưu nhà cung cấp mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'approval_status' => 'required|in:pending,approved,rejected',
            'license_number' => 'nullable|string|max:50',
            'license_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Xử lý file logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('providers/logos', $logoName, 'public');
            $data['logo'] = $logoPath;
        }

        // Xử lý file giấy phép
        if ($request->hasFile('license_file')) {
            $license = $request->file('license_file');
            $licenseName = time() . '_' . $license->getClientOriginalName();
            $licensePath = $license->storeAs('providers/licenses', $licenseName, 'public');
            $data['license_file'] = $licensePath;
        }

        ServiceProvider::create($data);

        return redirect()->route('admin.providers.index')->with('success', 'Thêm nhà cung cấp thành công');
    }

    /**
     * Hiển thị chi tiết nhà cung cấp
     */
    public function show(ServiceProvider $provider)
    {
        return view('admin.providers.show', compact('provider'));
    }

    /**
     * Hiển thị form chỉnh sửa nhà cung cấp
     */
    public function edit(ServiceProvider $provider)
    {
        $users = User::all();
        $approvalStatuses = ServiceProvider::getApprovalStatuses();
        $statuses = ServiceProvider::getStatuses();
        return view('admin.providers.edit', compact('provider', 'users', 'approvalStatuses', 'statuses'));
    }

    /**
     * Cập nhật thông tin nhà cung cấp
     */
    public function update(Request $request, ServiceProvider $provider)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,user_id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'approval_status' => 'required|in:pending,approved,rejected',
            'license_number' => 'nullable|string|max:50',
            'license_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Xử lý file logo
        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($provider->logo) {
                Storage::disk('public')->delete($provider->logo);
            }
            
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('providers/logos', $logoName, 'public');
            $data['logo'] = $logoPath;
        }

        // Xử lý file giấy phép
        if ($request->hasFile('license_file')) {
            // Xóa file cũ nếu có
            if ($provider->license_file) {
                Storage::disk('public')->delete($provider->license_file);
            }
            
            $license = $request->file('license_file');
            $licenseName = time() . '_' . $license->getClientOriginalName();
            $licensePath = $license->storeAs('providers/licenses', $licenseName, 'public');
            $data['license_file'] = $licensePath;
        }

        $provider->update($data);

        return redirect()->route('admin.providers.index')->with('success', 'Cập nhật nhà cung cấp thành công');
    }

    /**
     * Xóa nhà cung cấp
     */
    public function destroy(ServiceProvider $provider)
    {
        // Xóa logo
        if ($provider->logo) {
            Storage::disk('public')->delete($provider->logo);
        }
        
        // Xóa file giấy phép
        if ($provider->license_file) {
            Storage::disk('public')->delete($provider->license_file);
        }
        
        $provider->delete();

        return redirect()->route('admin.providers.index')->with('success', 'Xóa nhà cung cấp thành công');
    }
    
    /**
     * Thay đổi trạng thái nhà cung cấp
     */
    public function changeStatus(Request $request, ServiceProvider $provider)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $provider->status = $request->status;
        $provider->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }
}