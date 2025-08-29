<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
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
            'name' => 'min:3',
            'description' => 'min:5',
            'picture' => 'image|mimes:jpeg,png,jpg,webp,svg',
            'price' => 'integer',
            'is_active' => 'in:0,1',
            'category_id' => 'integer|exists:categories,id',
        ];
    }
}
