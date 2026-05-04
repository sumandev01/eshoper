<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Thana;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        // return view('admin.locations.index');
    }

    public function storeDivision(Request $request)
    {
        // Logic to store a new division
    }

    public function storeDistrict(Request $request)
    {
        // Logic to store a new district
    }

    public function storeThana(Request $request)
    {
        // Logic to store a new thana
    }

    public function ajaxStoreDivision()
    {
        // Logic to return divisions in AJAX format
    }

    public function ajaxStoreDistrict(Request $request)
    {
        $districts = District::where('division_id', '=', $request->division_id, 'and')->get(['id', 'name', 'division_id']);
        return response()->json($districts);
    }

    public function ajaxStoreThana(Request $request)
    {
        $thanas = Thana::where('district_id', '=', $request->district_id, 'and')->get(['id', 'name', 'district_id']);
        return response()->json($thanas);
    }
}
