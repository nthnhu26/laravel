<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules()
    {
        return [
            'name.vi' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'address.vi' => 'required|string|max:255',
            'address.en' => 'nullable|string|max:255',
            'description.vi' => 'nullable|string',
            'description.en' => 'nullable|string',
            'type' => 'required|in:luxury,budget,resort,homestay,beach_view,family,business,villa,apartment,other',
            'provider_id' => 'nullable|exists:service_providers,provider_id',
            'is_admin_managed' => 'boolean',
            'contact_info.email' => 'required_unless:provider_id,!=,null|email|max:100',
            'contact_info.phone' => 'required_unless:provider_id,!=,null|string|max:20',
            'contact_info.website' => 'nullable|url|max:255',
            'status' => 'in:active,inactive',
            'price_range' => 'nullable|string|max:100',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,amenity_id',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'cancellation_policy.vi' => 'nullable|string',
            'cancellation_policy.en' => 'nullable|string',
        ];
    }

    public function prepareForValidation()
    {
        // If provider_id is set, remove contact_info
        if ($this->filled('provider_id')) {
            $this->merge(['contact_info' => null]);
        }
    }
}