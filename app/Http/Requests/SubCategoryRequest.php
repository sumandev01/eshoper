<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SubCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('main_thumb')) {
            $this->merge([
                'slug' => Str::slug($this->slug),
                'media_id' => $this->input('main_thumb'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $subCategory = $this->route('subCategory');
        $name = $this->method() === 'PUT' ? 'required|string|max:255|unique:sub_categories,name,' . $this->subCategory->id : 'required|string|max:255|unique:sub_categories,name';
        $slug = $this->method() === 'PUT' ? 'required|string|max:255|unique:sub_categories,slug,' . $this->subCategory->id : 'nullable|string|max:255|unique:sub_categories,slug';
        $category_id = $this->method() === 'PUT' ? 'required|exists:categories,id' : 'required|exists:categories,id';
        $media_id = $this->method() === 'PUT' ? 'nullable' : 'nullable|exists:media,id';
        return [
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category_id,
            'media_id' => $media_id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'slug.required' => 'Slug is required',
            'slug.unique' => 'This Slug has already been created.',
            'name.unique' => 'This name already exists.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'The selected category is invalid or missing.',
            'media_id.exists' => 'The selected image is invalid or missing.',
        ];
    }
}
