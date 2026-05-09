<?php

namespace App\Repositories;

use App\Models\District;
use App\Models\Division;
use App\Models\ShippingAddress;
use App\Models\Thana;

class ShippingRepository
{
    public static function storeByRequest($request, $order)
    {
        if ($request->shipto == 1) {
            $division_name = Division::find($request->shipping_division_id)->name;
            $district_name = District::find($request->shipping_district_id)->name;
            $thana_name = Thana::find($request->shipping_thana_id)->name;

            ShippingAddress::create([
                'order_id' => $order->id,
                'name' => $request->shipping_name,
                'mobile' => $request->shipping_mobile,
                'email' => $request->shipping_email,
                'division' => $division_name,
                'district' => $district_name,
                'thana' => $thana_name,
                'address' => $request->shipping_address,
                'zip' => $request->shipping_zip
            ]);
        } else {
            $division_name = Division::find($request->billing_division_id)->name;
            $district_name = District::find($request->billing_district_id)->name;
            $thana_name = Thana::find($request->billing_thana_id)->name;

            ShippingAddress::create([
                'order_id' => $order->id,
                'name' => $request->billing_name,
                'mobile' => $request->billing_mobile,
                'email' => $request->billing_email,
                'division' => $division_name,
                'district' => $district_name,
                'thana' => $thana_name,
                'address' => $request->billing_address,
                'zip' => $request->billing_zip
            ]);
        }
    }
}
