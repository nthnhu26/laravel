<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->role === 'admin';
    }

    public function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'name.vi' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'description.vi' => 'nullable|string',
            'description.en' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'area' => 'nullable|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'bed_type' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ];
    }
}