<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function index()
    {
        $countries = Country::with('states')->get();
        return view('dashboard.location.index', compact('countries'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('dashboard.location.add', compact('countries'));
    }

    public function storeCountry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
            'code' => 'nullable|string|max:5',
        ]);

        Country::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.location.index')->with('success', 'Country created successfully.');
    }

    public function storeState(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
        ]);

        State::create([
            'country_id' => $request->country_id,
            'name' => $request->name,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.location.index')->with('success', 'State created successfully.');
    }

    public function syncStatesOnline(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $country = Country::findOrFail($request->country_id);

        try {
            $response = Http::post('https://countriesnow.space/api/v0.1/countries/states', [
                'country' => $country->name
            ]);

            if ($response->successful() && !$response->json('error')) {
                $states = $response->json('data.states');
                $count = 0;
                foreach ($states as $stateData) {
                    $state = State::firstOrCreate([
                        'country_id' => $country->id,
                        'name' => $stateData['name']
                    ]);
                    if ($state->wasRecentlyCreated) {
                        $count++;
                    }
                }
                return back()->with('success', $count . ' new states synced successfully from online for ' . $country->name . '!');
            }

            return back()->with('error', 'Could not sync states. The API might not support this country name.');
        } catch (\Exception $e) {
            return back()->with('error', 'Online API is unreachable at the moment.');
        }
    }

    public function destroyCountry($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        return back()->with('success', 'Country deleted successfully.');
    }

    public function destroyState($id)
    {
        $state = State::findOrFail($id);
        $state->delete();
        return back()->with('success', 'State deleted successfully.');
    }

    // AJAX Endpoint for Checkout Page
    public function getStatesByCountry($country_id)
    {
        $states = State::with('shippingCost')->where('country_id', $country_id)->where('status', 1)->get();
        return response()->json($states);
    }
}
