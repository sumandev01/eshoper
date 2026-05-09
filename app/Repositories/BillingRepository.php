<?php

namespace App\Repositories;

use App\Models\BillingAddress;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;

class BillingRepository
{
    public static function storeByRequest($request, $order)
    {
        $division_name = Division::find($request->billing_division_id)->name;
        $district_name = District::find($request->billing_district_id)->name;
        $thana_name = Thana::find($request->billing_thana_id)->name;

        BillingAddress::create([
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
