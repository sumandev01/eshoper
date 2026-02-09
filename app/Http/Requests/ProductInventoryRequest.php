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
        if (!$mediaId) {
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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'product_id' => 'required|exists:products,id',
            'size_id'    => $isUpdate ? 'nullable|exists:sizes,id' : 'required|exists:sizes,id',
            'color_id'   => $isUpdate ? 'nullable|exists:colors,id' : 'required|exists:colors,id',
            'price'      => 'nullable|numeric|min:0',
            'discount'   => 'nullable|numeric|min:0',
            'stock'      => 'required|integer|min:0',
            'media_id'   => 'nullable|exists:media,id'
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
