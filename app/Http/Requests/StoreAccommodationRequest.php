<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccommodationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:hotel,villa,homestay,penginapan',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'price_range' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:draft,published',
            'cover_image' => 'required|image|max:2048',
        ];
    }
}
