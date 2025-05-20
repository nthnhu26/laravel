<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if (!$file->isValid()) {
                    return response()->json(['error' => 'File không hợp lệ'], 400);
                }

                $path = $file->store('images/summernote', 'public');
                $url = Storage::url($path);

                return response()->json(['url' => $url]);
            }

            return response()->json(['error' => 'Không có file được tải lên'], 400);
        } catch (\Exception $e) {
            \Log::error('Lỗi tải ảnh Summernote: ' . $e->getMessage());
            return response()->json(['error' => 'Lỗi server khi tải ảnh'], 500);
        }
    }
}