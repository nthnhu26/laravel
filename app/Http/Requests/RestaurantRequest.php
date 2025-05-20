<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'name.vi' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'description.vi' => 'nullable|string',
            'description.en' => 'nullable|string',
            'address.vi' => 'required|string|max:255',
            'address.en' => 'nullable|string|max:255',
            'contact_info.vi' => 'required|json',
            'contact_info.en' => 'nullable|json',
            'type' => 'required|in:vietnamese,seafood,asian,western,fusion,vegetarian,buffet,street_food,other',
            'price_category' => 'required|in:budget,mid_range,fine_dining,luxury',
            'provider_id' => 'nullable|exists:service_providers,provider_id',
            'is_admin_managed' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'opening_hours' => 'nullable|json',
            'price_range' => 'nullable|string|max:50',
            'cancellation_policy.vi' => 'nullable|string',
            'cancellation_policy.en' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,amenity_id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}