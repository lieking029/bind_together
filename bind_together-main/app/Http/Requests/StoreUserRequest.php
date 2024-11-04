<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'student_number' => ['nullable', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'contact' => ['nullable', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'year_level' => ['nullable', 'int'],
            'sport_id' => ['nullable', 'exists:sports,id'],
            'course_id' => ['nullable', 'exists:course,id'],
            'campus_id' => ['nullable', 'exists:campus,id'],
            'program_id' => ['nullable', 'exists:program,id'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'avatar' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string'],
        ];
    }
}
