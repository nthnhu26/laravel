<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantRequest;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    

    public function index()
    {
        $restaurants = Restaurant::all();
        return view('admin.restaurants.index', [
            'resourceName' => 'Nhà hàng',
            'restaurants' => $restaurants,
        ]);
    }

    public function create()
    {
        $amenities = \App\Models\Amenity::all();
        $providers = \App\Models\ServiceProvider::where('status', 'active')->get();
        return view('admin.restaurants.create', [
            'resourceName' => 'Nhà hàng',
            'amenities' => $amenities,
            'providers' => $providers,
        ]);
    }

    public function store(RestaurantRequest $request)
    {
        $validated = $request->validated();
        $restaurant = Restaurant::create($validated);

        if (isset($validated['amenities'])) {
            $restaurant->amenities()->sync($validated['amenities']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('images', 'public');
                $restaurant->images()->create([
                    'url' => $path,
                    'is_featured' => $index === 0,
                ]);
            }
        }

        sweetalert()->success('Nhà hàng được tạo thành công.');

        return $request->input('continue') ? redirect()->route('admin.restaurants.edit', $restaurant) : redirect()->route('admin.restaurants.index');
    }

    public function edit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $amenities = \App\Models\Amenity::all();
        $providers = \App\Models\ServiceProvider::where('status', 'active')->get();
        return view('admin.restaurants.edit', [
            'resourceName' => 'Nhà hàng',
            'entity' => $restaurant,
            'amenities' => $amenities,
            'providers' => $providers,
        ]);
    }

    public function update(RestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $validated = $request->validated();
        $restaurant->update($validated);

        if (isset($validated['amenities'])) {
            $restaurant->amenities()->sync($validated['amenities']);
        } else {
            $restaurant->amenities()->detach();
        }

        if ($request->hasFile('images')) {
            foreach ($restaurant->images as $image) {
                Storage::disk('public')->delete($image->url);
                $image->delete();
            }
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('images', 'public');
                $restaurant->images()->create([
                    'url' => $path,
                    'is_featured' => $index === 0,
                ]);
            }
        }

        flash()->success('Nhà hàng được cập nhật thành công.');

        return $request->input('continue') ? redirect()->route('admin.restaurants.edit', $restaurant) : redirect()->route('admin.restaurants.index');
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        foreach ($restaurant->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $restaurant->amenities()->detach();
        $restaurant->delete();

        flash()->success('Nhà hàng đã được xóa.');

        return redirect()->route('admin.restaurants.index');
    }
}