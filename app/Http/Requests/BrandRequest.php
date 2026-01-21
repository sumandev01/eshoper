<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
        $name = $this->method() === 'PUT' ? 'required|string|max:255|unique:brands,name,' . $this->brand->id : 'required|string|max:255|unique:brands,name';
        $slug = $this->method() === 'PUT' ? 'required|string|max:255|unique:brands,slug,' . $this->brand->id : 'nullable|string|max:255|unique:brands,slug';
        $media_id = $this->method() === 'PUT' ? 'nullable' : 'nullable|exists:media,id';
        return [
            'name' => $name,
            'slug' => $slug,
            'media_id' => $media_id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Brand name is required',
            'slug.required' => 'Brand slug is required',
            'slug.unique' => 'This brand slug has already been created.',
            'name.unique' => 'This brand name already exists.',
            'media_id.exists' => 'The selected brand image is invalid or missing.',
        ];
    }
}
