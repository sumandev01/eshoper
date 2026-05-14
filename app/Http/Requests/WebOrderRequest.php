<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebOrderRequest extends FormRequest
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
        $billing_name = 'required|string|max:255';
        $billing_mobile = 'required|numeric';
        $billing_email = 'required|email:rfc,dns|max:255';
        $billing_division_id = 'required|exists:divisions,id';
        $billing_district_id = 'required|exists:districts,id';
        $billing_thana_id = 'required|exists:thanas,id';
        $billing_address = 'required|string|max:255';
        $billing_zip = 'required|string|max:255';
        $note = 'nullable|string|max:255';
        $shipto = 'in:0,1';
        $shipping_name = 'nullable|string|max:255|required_if:shipto,1';
        $shipping_mobile = 'nullable|numeric|required_if:shipto,1';
        $shipping_email = 'nullable|email:rfc,dns|max:255|required_if:shipto,1';
        $shipping_division_id = 'nullable|exists:divisions,id|required_if:shipto,1';
        $shipping_district_id = 'nullable|exists:districts,id|required_if:shipto,1';
        $shipping_thana_id = 'nullable|exists:thanas,id|required_if:shipto,1';
        $shipping_address = 'nullable|string|max:255|required_if:shipto,1';
        $shipping_zip = 'nullable|string|max:255|required_if:shipto,1';
        $coupon_code = 'nullable|exists:coupons,id';
        $shipping_charge = 'required|numeric|min:0';
        $payment = 'required';

        return [
            'billing_name' => $billing_name,
            'billing_mobile' => $billing_mobile,
            'billing_email' => $billing_email,
            'billing_division_id' => $billing_division_id,
            'billing_district_id' => $billing_district_id,
            'billing_thana_id' => $billing_thana_id,
            'billing_address' => $billing_address,
            'billing_zip' => $billing_zip,
            'note' => $note,
            'shipto' => $shipto,
            'shipping_name' => $shipping_name,
            'shipping_mobile' => $shipping_mobile,
            'shipping_email' => $shipping_email,
            'shipping_division_id' => $shipping_division_id,
            'shipping_district_id' => $shipping_district_id,
            'shipping_thana_id' => $shipping_thana_id,
            'shipping_address' => $shipping_address,
            'shipping_zip' => $shipping_zip,
            'coupon_code' => $coupon_code,
            'shipping_charge' => $shipping_charge,
            'payment' => $payment,
        ];
    }

    public function messages()
    {
        return [
            'billing_name.required' => 'Billing name is required.',
            'billing_mobile.required' => 'Billing mobile is required.',
            'billing_email.required' => 'Billing email is required.',
            'billing_division_id.required' => 'Billing division is required.',
            'billing_district_id.required' => 'Billing district is required.',
            'billing_thana_id.required' => 'Billing thana is required.',
            'billing_address.required' => 'Billing address is required.',
            'billing_zip.required' => 'Billing zip is required.',
            'shipping_name.required_if' => 'Shipping name is required.',
            'shipping_mobile.required_if' => 'Shipping mobile is required.',
            'shipping_email.required_if' => 'Shipping email is required.',
            'shipping_division_id.required_if' => 'Shipping division is required.',
            'shipping_district_id.required_if' => 'Shipping district is required.',
            'shipping_thana_id.required_if' => 'Shipping thana is required.',
            'shipping_address.required_if' => 'Shipping address is required.',
            'shipping_zip.required_if' => 'Shipping zip is required.',
            'payment.required' => 'Payment method is required.',
        ];
    }
}
