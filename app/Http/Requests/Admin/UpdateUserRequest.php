<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string',
            'phone' => [
                'required',
                'min:11',
                'max:11',
                'regex:/^[0-9]{11}$/',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'password' => 'nullable|min:8|',
            'role_id' => 'integer|exists:roles,id',
            'is_active' => 'required|boolean|in:1,0',
        ];
    }
}
