<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCulinaryPlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:restoran,cafe,warung,rumah_makan',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'price_range' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|max:2048',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
            'photo_captions' => 'nullable|array',
            'photo_captions.*' => 'nullable|string|max:255',
            'deleted_photo_ids' => 'nullable|array',
            'deleted_photo_ids.*' => 'integer|exists:gallery_photos,id',
        ];
    }
}
