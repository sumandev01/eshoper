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
        // Determine media_id from various possible inputs and set it in the request data
        $mediaId = $this->input('main_thumb') ?? $this->input('inventory_media');
        // If media_id is still not found, check for dynamically named inputs
        if(!$mediaId){
            foreach ($this->all() as $key => $value) {
                if (str_starts_with($key, 'inventory_media_')) {
                    $mediaId = $value;
                    break;
                }
            }
        }
        // If media_id is found, set it in the request data
        if ($mediaId) {
            $this->merge([
                'media_id' => $mediaId,
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
        $productId = $this->method() === 'PUT' ? 'required|exists:products,id' : 'required|exists:products,id';
        $size_id = $this->method() === 'PUT' ? 'nullable|exists:sizes,id' : 'required|exists:sizes,id';
        $color_id = $this->method() === 'PUT' ? 'nullable|exists:colors,id' : 'required|exists:colors,id';
        $price = $this->method() === 'PUT' ? 'nullable|numeric|min:0' : 'nullable|numeric|min:0';
        $stock = $this->method() === 'PUT' ? 'required|integer|min:0' : 'required|integer|min:0';
        $media_id = $this->method() === 'PUT' ? 'nullable|exists:media,id' : 'nullable|exists:media,id';

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
