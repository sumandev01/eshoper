<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
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
        $name = $this->method() === 'PUT' ? 'required|string|max:255|unique:colors,name,' . $this->color->id : 'required|string|max:255|unique:colors,name';
        $color_code = $this->method() === 'PUT' ? 'required|string|max:255|unique:colors,color_code,' . $this->color->id : 'required|string|max:255|unique:colors,color_code';
        return [
            'name' => $name,
            'color_code' => $color_code
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'color_code.required' => 'Color code is required',
            'name.unique' => 'Name already exists.',
            'color_code.unique' => 'This color code already exists.',
        ];
    }
}
