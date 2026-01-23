<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductInventoryRequest extends FormRequest
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
        $productId = $this->method() === 'POST' ? 'required|exists:products,id' : 'required|exists:products,id';
        $size_id = $this->method() === 'POST' ? 'required' : 'required';
        $color_id = $this->method() === 'POST' ? 'required' : 'required';
        $price = $this->method() === 'POST' ? 'nullable|numeric|min:0' : 'nullable|numeric|min:0';
        $stock = $this->method() === 'POST' ? 'required|integer|min:0' : 'required|integer|min:0';
        $media_id = $this->method() === 'POST' ? 'nullable' : 'nullable';

        return [
            'product_id' => $productId,
            'size_id' => $size_id,
            'color_id' => $color_id,
            'price' => $price,
            'stock' => $stock,
            'media_id' => $media_id
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'The selected product does not exist.',
            'size_id.required' => 'Size is required.',
            'color_id.required' => 'Color is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'stock.required' => 'Stock is required.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock must be at least 0.',
        ];
    }
}
