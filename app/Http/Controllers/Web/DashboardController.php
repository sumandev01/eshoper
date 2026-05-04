<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('web.dashboard.index');
    }

    public function profile()
    {
        return view('web.dashboard.profile');
    }

    public function address()
    {
        $user = auth('web')->user();
        $divisions = Division::all();
        $shippingAddress = UserAddress::where('user_id', $user->id)->where('type', 'shipping')->first();
        $billingAddress = UserAddress::where('user_id', $user->id)->where('type', 'billing')->first();
        return view('web.dashboard.address', compact('user', 'divisions', 'shippingAddress', 'billingAddress'));
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required',
            'shipping_mobile' => 'required',
            'shipping_division_id' => 'required',
            'shipping_district_id' => 'required',
            'shipping_thana_id' => 'required',
            'shipping_address' => 'required',
            'billing_name' => 'required_if:billingToDifferent,on',
            'billing_mobile' => 'required_if:billingToDifferent,on',
            'billing_division_id' => 'required_if:billingToDifferent,on',
            'billing_district_id' => 'required_if:billingToDifferent,on',
            'billing_thana_id' => 'required_if:billingToDifferent,on',
            'billing_address' => 'required_if:billingToDifferent,on',
        ]);
        $userId = auth('web')->user()->id;
        if ($request->billingToDifferent == '1') {
            $address_default = '1';
        } else {
            $address_default = '0';
        };
        try {
            UserAddress::updateOrCreate(
                ['user_id' => $userId, 'type' => 'shipping'],
                [
                    'address_default' => $address_default,
                    'name' => $request->input('shipping_name'),
                    'mobile' => $request->input('shipping_mobile'),
                    'division_id' => $request->input('shipping_division_id'),
                    'district_id' => $request->input('shipping_district_id'),
                    'thana_id' => $request->input('shipping_thana_id'),
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
                        'division_id' => $request->input('billing_division_id'),
                        'district_id' => $request->input('billing_district_id'),
                        'thana_id' => $request->input('billing_thana_id'),
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
                        'division_id' => $request->input('shipping_division_id'),
                        'district_id' => $request->input('shipping_district_id'),
                        'thana_id' => $request->input('shipping_thana_id'),
                        'address' => $request->input('shipping_address'),
                        'zip' => $request->input('shipping_zip'),
                    ]
                );
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('user.address')->with('success', 'Address updated successfully.');
    }
}
