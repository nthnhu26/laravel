<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{


    public function index()
    {
        $images = Image::all();
        return view('admin.images.index', [
            'resourceName' => 'Hình ảnh',
            'images' => $images,
        ]);
    }

    public function create()
    {
        return view('admin.images.create', [
            'resourceName' => 'Hình ảnh',
        ]);
    }

    public function store(ImageRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('url')) {
            $path = $request->file('url')->store('images', 'public');
            $validated['url'] = $path;
        }

        $image = Image::create($validated);

        flash()->success('Hình ảnh được tạo thành công.');

        return $request->input('continue') ? redirect()->route('admin.images.edit', $image) : redirect()->route('admin.images.index');
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('admin.images.edit', [
            'resourceName' => 'Hình ảnh',
            'entity' => $image,
        ]);
    }

    public function update(ImageRequest $request, $id)
    {
        $image = Image::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('url')) {
            // Delete old image
            Storage::disk('public')->delete($image->url);
            $path = $request->file('url')->store('images', 'public');
            $validated['url'] = $path;
        }

        $image->update($validated);

        flash()->success('Hình ảnh được cập nhật thành công.');

        return $request->input('continue') ? redirect()->route('admin.images.edit', $image) : redirect()->route('admin.images.index');
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        Storage::disk('public')->delete($image->url);
        $image->delete();

        flash()->success('Hình ảnh đã được xóa.');

        return redirect()->route('admin.images.index');
    }
}