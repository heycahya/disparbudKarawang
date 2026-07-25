<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|in:wisata,budaya,ekraf,event,lainnya',
            'media' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
