<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return view('admin.hotels.index', [
            'resourceName' => 'Khách sạn',
            'hotels' => $hotels,
        ]);
    }

    public function create()
    {
        $amenities = Amenity::all();
        $providers = ServiceProvider::where('status', 'active')->get();
        Log::info('Goong API Key: ' . config('services.goong.key'));
       Log::info('Goong Maptiles Key: ' . config('services.goong.maptiles_key'));
        return view('admin.hotels.create', [
            'resourceName' => 'Khách sạn',
            'amenities' => $amenities,
            'providers' => $providers,
            'goong_api_key' => config('services.goong.key'),
            'goong_maptiles_key' => config('services.goong.maptiles_key'),
        ]);
    }

    public function store(HotelRequest $request)
    {
        $validated = $request->validated();

        if (!empty($validated['contact_info'])) {
            $validated['contact_info'] = json_encode($validated['contact_info']);
        }

        $validated['is_admin_managed'] = !$validated['provider_id'];
        $hotel = Hotel::create($validated);

        if (isset($validated['amenities'])) {
            $hotel->amenities()->sync($validated['amenities']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('images', 'public');
                $hotel->images()->create([
                    'url' => $path,
                    'is_featured' => $index === 0,
                ]);
            }
        }

        flash()->success('Khách sạn được tạo thành công.');
        return $request->input('continue') ? redirect()->route('admin.hotels.edit', $hotel) : redirect()->route('admin.hotels.index');
    }

    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $amenities = Amenity::all();
        $providers = ServiceProvider::where('status', 'active')->get();
       Log::info('Goong API Key: ' . config('services.goong.key'));
        Log::info('Goong Maptiles Key: ' . config('services.goong.maptiles_key'));
        return view('admin.hotels.edit', [
            'resourceName' => 'Khách sạn',
            'entity' => $hotel,
            'amenities' => $amenities,
            'providers' => $providers,
            'goong_api_key' => config('services.goong.key'),
            'goong_maptiles_key' => config('services.goong.maptiles_key'),
        ]);
    }

    public function update(HotelRequest $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $validated = $request->validated();

        if (!empty($validated['contact_info'])) {
            $validated['contact_info'] = json_encode($validated['contact_info']);
        }

        $validated['is_admin_managed'] = !$validated['provider_id'];
        $hotel->update($validated);

        if (isset($validated['amenities'])) {
            $hotel->amenities()->sync($validated['amenities']);
        } else {
            $hotel->amenities()->detach();
        }

        if ($request->hasFile('images')) {
            foreach ($hotel->images as $image) {
                Storage::disk('public')->delete($image->url);
                $image->delete();
            }
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('images', 'public');
                $hotel->images()->create([
                    'url' => $path,
                    'is_featured' => $index === 0,
                ]);
            }
        }

        sweetalert()->success('Khách sạn được cập nhật thành công.');
        return $request->input('continue') ? redirect()->route('admin.hotels.edit', $hotel) : redirect()->route('admin.hotels.index');
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        foreach ($hotel->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $hotel->amenities()->detach();
        $hotel->delete();

        flash()->success('Khách sạn đã được xóa.');
        return redirect()->route('admin.hotels.index');
    }
}