<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCultureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|in:kesenian,tradisi,warisan_budaya',
            'description' => 'required|string',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|max:2048', // optional on update
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
            'photo_captions' => 'nullable|array',
            'photo_captions.*' => 'nullable|string|max:255',
            'deleted_photo_ids' => 'nullable|array',
            'deleted_photo_ids.*' => 'integer|exists:gallery_photos,id',
        ];
    }
}
