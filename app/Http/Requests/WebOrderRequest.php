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
        $billing_country_id = 'required|exists:countries,id';
        $billing_state_id = 'required|exists:states,id';
        $billing_city = 'required|string|max:255';
        $billing_address = 'required|string|max:255';
        $billing_zip = 'required|string|max:255';
        $note = 'nullable|string|max:255';
        $shipto = 'in:0,1';
        $shipping_name = 'nullable|string|max:255|required_if:shipto,1';
        $shipping_mobile = 'nullable|numeric|required_if:shipto,1';
        $shipping_email = 'nullable|email:rfc,dns|max:255|required_if:shipto,1';
        $shipping_country_id = 'nullable|exists:countries,id|required_if:shipto,1';
        $shipping_state_id = 'nullable|exists:states,id|required_if:shipto,1';
        $shipping_city = 'nullable|string|max:255|required_if:shipto,1';
        $shipping_address = 'nullable|string|max:255|required_if:shipto,1';
        $shipping_zip = 'nullable|string|max:255|required_if:shipto,1';
        $coupon_code = 'nullable|exists:coupons,id';
        $shipping_charge = 'required|numeric|min:0';
        $payment = 'required';
        $sender_number = 'required_if:payment,manual|nullable|string|max:255';
        $transaction_id = 'required_if:payment,manual|nullable|string|max:255';

        return [
            'billing_name' => $billing_name,
            'billing_mobile' => $billing_mobile,
            'billing_email' => $billing_email,
            'billing_country_id' => $billing_country_id,
            'billing_state_id' => $billing_state_id,
            'billing_city' => $billing_city,
            'billing_address' => $billing_address,
            'billing_zip' => $billing_zip,
            'note' => $note,
            'shipto' => $shipto,
            'shipping_name' => $shipping_name,
            'shipping_mobile' => $shipping_mobile,
            'shipping_email' => $shipping_email,
            'shipping_country_id' => $shipping_country_id,
            'shipping_state_id' => $shipping_state_id,
            'shipping_city' => $shipping_city,
            'shipping_address' => $shipping_address,
            'shipping_zip' => $shipping_zip,
            'coupon_code' => $coupon_code,
            'shipping_charge' => $shipping_charge,
            'payment' => $payment,
            'sender_number' => $sender_number,
            'transaction_id' => $transaction_id,
        ];
    }

    public function messages()
    {
        return [
            'billing_name.required' => 'Billing name is required.',
            'billing_mobile.required' => 'Billing mobile is required.',
            'billing_email.required' => 'Billing email is required.',
            'billing_country_id.required' => 'Billing country is required.',
            'billing_state_id.required' => 'Billing state/province is required.',
            'billing_city.required' => 'Billing city is required.',
            'billing_address.required' => 'Billing address is required.',
            'billing_zip.required' => 'Billing zip is required.',
            'shipping_name.required_if' => 'Shipping name is required.',
            'shipping_mobile.required_if' => 'Shipping mobile is required.',
            'shipping_email.required_if' => 'Shipping email is required.',
            'shipping_country_id.required_if' => 'Shipping country is required.',
            'shipping_state_id.required_if' => 'Shipping state/province is required.',
            'shipping_city.required_if' => 'Shipping city is required.',
            'shipping_address.required_if' => 'Shipping address is required.',
            'shipping_zip.required_if' => 'Shipping zip is required.',
            'payment.required' => 'Payment method is required.',
            'sender_number.required_if' => 'Please enter the Sender Account Number for manual payment.',
            'transaction_id.required_if' => 'Please enter your Transaction ID for manual payment.',
        ];
    }
}
