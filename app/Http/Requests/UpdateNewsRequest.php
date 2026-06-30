<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'news_category_id' => 'required|exists:news_categories,id',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|max:2048', // optional on update
        ];
    }
}
