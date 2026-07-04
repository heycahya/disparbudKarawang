<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCultureRequest extends FormRequest
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
            'cover_image' => 'required|image|max:2048', // max 2MB
        ];
    }
}
