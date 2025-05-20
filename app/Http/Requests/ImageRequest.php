<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->role === 'admin';
    }

    public function rules()
    {
        $rules = [
            'entity_type' => 'required|in:hotel,room,restaurant,attraction,dish,tour,transport,review,post,event',
            'entity_id' => 'required|integer',
            'url' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ];

        // Make 'url' required only for store (create) action
        if ($this->isMethod('post')) {
            $rules['url'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }
}