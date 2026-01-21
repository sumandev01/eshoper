<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CategoryRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->has('main_thumb')) {
            $this->merge([
                'slug' => Str::slug($this->slug),
                'media_id' => $this->input('main_thumb'),
            ]);
        }
    }
    public function rules(): array
    {
        $name = $this->method() === 'PUT' ? 'required|string|max:255|unique:categories,name,' . $this->category->id : 'required|string|max:255|unique:categories,name';
        $slug = $this->method() === 'PUT' ? 'required|string|max:255|unique:categories,slug,' . $this->category->id : 'nullable|string|max:255|unique:categories,slug';
        return [
            'name' => $name,
            'slug' => $slug,
            'media_id' => 'nullable|exists:media,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'slug.required' => 'Slug is required',
            'slug.unique' => 'This Slug has already been created.',
            'name.unique' => 'This Name already exists.',
            'media_id.exists' => 'The selected image is invalid or missing.',
        ];
    }
}
