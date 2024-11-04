<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRegistrationRequest extends FormRequest
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
            'activity_id' => ['required', 'exists:activities,id'],
            'height' => ['required', 'numeric', 'max:300'],
            'weight' => ['required', 'numeric', 'max:500'],
            'emergency_contact' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'string', 'max:255'],
            'certificate_of_registration' => ['required', 'file', 'mimes:jpeg,png,pdf', 'max:2048'],
            'parent_consent' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:2048'],
            'other_file' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:2048'],
            'photo_copy_id' => ['required', 'file', 'mimes:jpeg,png,pdf', 'max:2048'],
        ];
    }
}
