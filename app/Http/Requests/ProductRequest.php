<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
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

    public function rules(): array
    {
        // Determine if the request is for creating a new product (POST) or updating an existing one (PUT/PATCH)
        $isPost = $this->isMethod('POST');
        $productId = $this->route('product');
        $id = is_object($productId) ? $productId->id : $productId;

        return [
            'name' => $isPost ? 'required|unique:products,name' : 'required|unique:products,name,' . $id,
            'slug' => $isPost ? 'nullable|unique:products,slug' : 'required|unique:products,slug,' . $id,
            'sku' => $isPost ? 'required|unique:products,sku' : 'required|unique:products,sku,' . $id,
            'quantity' => 'required|numeric|min:0',
            'short_description' => $isPost ? 'required|string|max:500' : 'nullable|string|max:500',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'sale_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|lt:sale_price',
            'buy_price' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'media_id' => 'nullable|exists:media,id',
            'status' => 'required|numeric|in:0,1',
            'product_galleries' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => [
                'required',
                Rule::exists('sub_categories', 'id')->where(function ($query) {
                    return $query->where('category_id', $this->category_id);
                }),
            ],
            'brand_id' => 'nullable|exists:brands,id',
            'tag_id' => 'nullable|array',
            'meta_title' => 'nullable|string|min:30|max:60',
            'meta_description' => 'nullable|string|min:120|max:160',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.unique' => 'Product name already exists.',
            'slug.required' => 'Product slug is required.',
            'slug.unique' => 'Product slug already exists.',
            'sku.required' => 'Product SKU is required.',
            'sku.unique' => 'Product SKU already exists.',
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be a number.',
            'quantity.min' => 'Quantity must be at least 0.',
            'short_description.required' => 'Short description is required.',
            'short_description.string' => 'Short description must be a string.',
            'sale_price.required' => 'Sale price is required.',
            'sale_price.numeric' => 'Sale price must be a number.',
            'sale_price.min' => 'Sale price must be at least 0.',
            'discount.lt' => 'Discount must be less than sale price.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'sub_category_id.required' => 'Subcategory is required.',
            'sub_category_id.exists' => 'Selected subcategory does not exist.',
        ];
    }
}