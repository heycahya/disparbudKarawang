<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventBroadcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'public';
    }

    public function rules(): array
    {
        return [
            'organization' => ['required', 'string', 'max:255'],
            'event_name' => ['required', 'string', 'max:255'],
            'event_location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['required', 'string'],
            'proposal' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // max 5MB
        ];
    }
}
