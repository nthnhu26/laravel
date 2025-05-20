<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransportationController extends Controller
{
    /**
     * Display a listing of the transportation.
     */
    public function index()
    {
        $user = Auth::user();
        
        // If admin, show all transportation
        if ($user->role === 'admin') {
            $transportations = Transportation::with('provider')->paginate(10);
        } 
        // If provider, show only their transportation
        else {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'You need to register as a service provider first.');
            }
            
            $transportations = Transportation::where('provider_id', $provider->provider_id)
                ->paginate(10);
        }
        
        return view('admin.transportation.index', compact('transportations'));
    }

    /**
     * Show the form for creating a new transportation.
     */
    public function create()
    {
        $user = Auth::user();
        
        // If admin, get all providers for dropdown
        if ($user->role === 'admin') {
            $providers = ServiceProvider::where('status', 'active')->get();
        } 
        // If provider, just get their provider info
        else {
            $providers = ServiceProvider::where('user_id', $user->user_id)
                ->where('status', 'active')
                ->get();
                
            if ($providers->isEmpty()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'You need to register as an active service provider first.');
            }
        }
        
        return view('admin.transportation.create', compact('providers'));
    }

    /**
     * Store a newly created transportation in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'type' => 'required|in:car,motorbike,bicycle,boat',
            'capacity' => 'required|integer|min:1',
            'price_per_day' => 'required|numeric|min:0',
            'provider_id' => 'required|exists:service_providers,provider_id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        // Check if user is provider and trying to use another provider's ID
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if ($provider->provider_id != $request->provider_id) {
                return redirect()->back()
                    ->with('error', 'You can only add transportation for your own provider account.')
                    ->withInput();
            }
        }

        $transportation = new Transportation();
        $transportation->name = $request->name;
        $transportation->type = $request->type;
        $transportation->capacity = $request->capacity;
        $transportation->price_per_day = $request->price_per_day;
        $transportation->provider_id = $request->provider_id;
        $transportation->status = 'available';

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/transportation', $imageName);
            $transportation->image_url = 'storage/transportation/' . $imageName;
        }

        $transportation->save();

        return redirect()->route('admin.transportation.index')
            ->with('success', 'Transportation added successfully.');
    }

    /**
     * Display the specified transportation.
     */
    public function show($id)
    {
        $transportation = Transportation::with(['provider', 'bookings.user'])
            ->findOrFail($id);
            
        // Check if user has permission to view this transportation
        $user = Auth::user();
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider || $provider->provider_id != $transportation->provider_id) {
                return redirect()->route('admin.transportation.index')
                    ->with('error', 'You do not have permission to view this transportation.');
            }
        }
        
        return view('admin.transportation.show', compact('transportation'));
    }

    /**
     * Show the form for editing the specified transportation.
     */
    public function edit($id)
    {
        $transportation = Transportation::findOrFail($id);
        
        // Check if user has permission to edit this transportation
        $user = Auth::user();
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider || $provider->provider_id != $transportation->provider_id) {
                return redirect()->route('admin.transportation.index')
                    ->with('error', 'You do not have permission to edit this transportation.');
            }
            $providers = [$provider];
        } else {
            $providers = ServiceProvider::where('status', 'active')->get();
        }
        
        return view('admin.transportation.edit', compact('transportation', 'providers'));
    }

    /**
     * Update the specified transportation in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'type' => 'required|in:car,motorbike,bicycle,boat',
            'capacity' => 'required|integer|min:1',
            'price_per_day' => 'required|numeric|min:0',
            'provider_id' => 'required|exists:service_providers,provider_id',
            'status' => 'required|in:available,booked',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $transportation = Transportation::findOrFail($id);
        
        // Check if user has permission to update this transportation
        $user = Auth::user();
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider || $provider->provider_id != $transportation->provider_id) {
                return redirect()->route('admin.transportation.index')
                    ->with('error', 'You do not have permission to update this transportation.');
            }
            
            // Ensure provider can't change provider_id
            if ($transportation->provider_id != $request->provider_id) {
                return redirect()->back()
                    ->with('error', 'You cannot change the provider for this transportation.')
                    ->withInput();
            }
        }

        $transportation->name = $request->name;
        $transportation->type = $request->type;
        $transportation->capacity = $request->capacity;
        $transportation->price_per_day = $request->price_per_day;
        $transportation->provider_id = $request->provider_id;
        $transportation->status = $request->status;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($transportation->image_url && Storage::exists('public/' . str_replace('storage/', '', $transportation->image_url))) {
                Storage::delete('public/' . str_replace('storage/', '', $transportation->image_url));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/transportation', $imageName);
            $transportation->image_url = 'storage/transportation/' . $imageName;
        }

        $transportation->save();

        return redirect()->route('admin.transportation.index')
            ->with('success', 'Transportation updated successfully.');
    }

    /**
     * Remove the specified transportation from storage.
     */
    public function destroy($id)
    {
        $transportation = Transportation::findOrFail($id);
        
        // Check if user has permission to delete this transportation
        $user = Auth::user();
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider || $provider->provider_id != $transportation->provider_id) {
                return redirect()->route('admin.transportation.index')
                    ->with('error', 'You do not have permission to delete this transportation.');
            }
        }
        
        // Check if there are any active bookings
        $activeBookings = $transportation->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
            
        if ($activeBookings > 0) {
            return redirect()->route('admin.transportation.index')
                ->with('error', 'Cannot delete transportation with active bookings.');
        }

        // Delete image if exists
        if ($transportation->image_url && Storage::exists('public/' . str_replace('storage/', '', $transportation->image_url))) {
            Storage::delete('public/' . str_replace('storage/', '', $transportation->image_url));
        }

        $transportation->delete();

        return redirect()->route('admin.transportation.index')
            ->with('success', 'Transportation deleted successfully.');
    }
}
