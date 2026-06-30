<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRakyatStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:masuk,ditinjau,disetujui,ditolak'],
            'admin_note' => ['nullable', 'string', 'required_if:status,ditolak'],
        ];
    }

    public function messages(): array
    {
        return [
            'admin_note.required_if' => 'Catatan admin wajib diisi jika pengajuan ditolak.',
        ];
    }
}
