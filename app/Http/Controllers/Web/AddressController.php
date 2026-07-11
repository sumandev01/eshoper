<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function address()
    {
        $user = auth('web')->user();
        $countries = \App\Models\Country::all();
        $shippingAddress = UserAddress::whereUserId($user->id)->whereType('shipping')->first();
        $billingAddress = UserAddress::whereUserId($user->id)->whereType('billing')->first();
        return view('web.dashboard.address', compact('user', 'countries', 'shippingAddress', 'billingAddress'));
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required',
            'shipping_mobile' => 'required',
            'shipping_country_id' => 'required',
            'shipping_state_id' => 'required',
            'shipping_city' => 'required',
            'shipping_address' => 'required',
            'billing_name' => 'required_if:billingToDifferent,on',
            'billing_mobile' => 'required_if:billingToDifferent,on',
            'billing_country_id' => 'required_if:billingToDifferent,on',
            'billing_state_id' => 'required_if:billingToDifferent,on',
            'billing_city' => 'required_if:billingToDifferent,on',
            'billing_address' => 'required_if:billingToDifferent,on',
        ]);
        $userId = auth('web')->user()->id;
        if ($request->billingToDifferent == '1') {
            $address_default = '1';
        } else {
            $address_default = '0';
        };
        DB::beginTransaction();
        try {
            UserAddress::updateOrCreate(
                ['user_id' => $userId, 'type' => 'shipping'],
                [
                    'address_default' => $address_default,
                    'name' => $request->input('shipping_name'),
                    'mobile' => $request->input('shipping_mobile'),
                    'country_id' => $request->input('shipping_country_id'),
                    'state_id' => $request->input('shipping_state_id'),
                    'city' => $request->input('shipping_city'),
                    'address' => $request->input('shipping_address'),
                    'zip' => $request->input('shipping_zip'),
                ]
            );
            if ($request->billingToDifferent == '1') {
                UserAddress::updateOrCreate(
                    ['user_id' => $userId, 'type' => 'billing'],
                    [
                        'address_default' => $address_default,
                        'name' => $request->input('billing_name'),
                        'mobile' => $request->input('billing_mobile'),
                        'country_id' => $request->input('billing_country_id'),
                        'state_id' => $request->input('billing_state_id'),
                        'city' => $request->input('billing_city'),
                        'address' => $request->input('billing_address'),
                        'zip' => $request->input('billing_zip'),
                    ]
                );
            } else {
                UserAddress::updateOrCreate(
                    ['user_id' => $userId, 'type' => 'billing'],
                    [
                        'address_default' => $address_default,
                        'name' => $request->input('shipping_name'),
                        'mobile' => $request->input('shipping_mobile'),
                        'country_id' => $request->input('shipping_country_id'),
                        'state_id' => $request->input('shipping_state_id'),
                        'city' => $request->input('shipping_city'),
                        'address' => $request->input('shipping_address'),
                        'zip' => $request->input('shipping_zip'),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('user.address')->with('success', 'Address updated successfully.');
    }
}
