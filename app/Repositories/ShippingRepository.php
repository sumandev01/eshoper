<?php

namespace App\Repositories;

use App\Models\ShippingAddress;

class ShippingRepository
{
    public static function storeByRequest($request, $order)
    {
        if ($request->shipto == 1) {
            $country_name = \App\Models\Country::find($request->shipping_country_id)?->name;
            $state_name = \App\Models\State::find($request->shipping_state_id)?->name;

            ShippingAddress::create([
                'order_id' => $order->id,
                'name' => $request->shipping_name,
                'mobile' => $request->shipping_mobile,
                'email' => $request->shipping_email,
                'country_name' => $country_name,
                'state_name' => $state_name,
                'city' => $request->shipping_city,
                'address' => $request->shipping_address,
                'zip' => $request->shipping_zip
            ]);
        } else {
            $country_name = \App\Models\Country::find($request->billing_country_id)?->name;
            $state_name = \App\Models\State::find($request->billing_state_id)?->name;

            ShippingAddress::create([
                'order_id' => $order->id,
                'name' => $request->billing_name,
                'mobile' => $request->billing_mobile,
                'email' => $request->billing_email,
                'country_name' => $country_name,
                'state_name' => $state_name,
                'city' => $request->billing_city,
                'address' => $request->billing_address,
                'zip' => $request->billing_zip
            ]);
        }
    }
}
