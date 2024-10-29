<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'address' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date'],
            'gender' => ['nullable',],
            'contact' => ['nullable', 'string', 'min:10', 'max:15'],
            'password' => [
                'nullable',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->letters()
            ], // Ensure new password follows the same rules
            'old_password' => ['required_with:password'], // Old password required only if changing password
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'], // Max 2MB for avatar
            'suffix' => ['nullable', 'string', 'max:10'], // Suffix is optional
            'campus_id' => ['nullable', 'exists:campuses,id'], // Ensure campus exists
            'program_id' => ['nullable', 'exists:programs,id'], // Ensure program exists
            'year_level' => ['nullable', 'string',], // Valid year levels
            'sport_id' => ['nullable', 'exists:sports,id'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
        ];
    }

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Remove password from the request if it's null or not provided
        if ($this->password == null) {
            $this->request->remove('password');
        }
    }
}
