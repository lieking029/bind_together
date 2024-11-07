<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'type' => ['required', 'integer'],
            'sport_id' => ['nullable', 'exists:sports,id'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            // 'attachment' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'target_player' => ['required', 'integer'],
        ];
    }
}
