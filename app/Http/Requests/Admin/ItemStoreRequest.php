<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
            'name' => 'required|min:3',
            'description' => 'required|min:5',
            'picture' => 'required|image|mimes:jpeg,png,jpg,webp,svg',
            'price' => 'required|integer',
            'is_active' => 'required|in:0,1',
            'category_id' => 'integer|exists:categories,id',
        ];
    }
}
