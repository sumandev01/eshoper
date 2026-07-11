<?php

namespace App\Repositories;

use App\Models\BillingAddress;

class BillingRepository
{
    public static function storeByRequest($request, $order)
    {
        $country_name = \App\Models\Country::find($request->billing_country_id)?->name;
        $state_name = \App\Models\State::find($request->billing_state_id)?->name;

        BillingAddress::create([
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
