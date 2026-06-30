<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTourismDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tourism_category_id' => 'required|exists:tourism_categories,id',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:draft,published',
            'cover_image' => 'required|image|max:2048', // max 2MB
        ];
    }
}
