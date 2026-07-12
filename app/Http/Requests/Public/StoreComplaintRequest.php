<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'public';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'attachment' => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                    $isDoc = in_array($extension, ['pdf', 'doc', 'docx']);
                    
                    if (!$isImage && !$isDoc) {
                        $fail('Format file harus berupa PDF, DOC, DOCX, JPG, JPEG, atau PNG.');
                        return;
                    }
                    
                    $sizeKB = $value->getSize() / 1024;
                    
                    if ($isImage && $sizeKB > 2048) {
                        $fail('Ukuran gambar maksimal adalah 2MB.');
                    }
                    
                    if ($isDoc && $sizeKB > 5120) {
                        $fail('Ukuran dokumen maksimal adalah 5MB.');
                    }
                },
            ],
        ];
    }
}
