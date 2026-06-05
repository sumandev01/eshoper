<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Division::with('districts.thanas')->get();
        $districts = District::with('division')->get();
        $thanas = Thana::with('district')->get();
        return view('dashboard.location.index', compact('locations', 'districts', 'thanas'));
    }

    public function create()
    {
        $divisions = Division::all();
        $districts = District::with('division')->get();
        $thanas = Thana::with('district')->get();
        return view('dashboard.location.add', compact('divisions', 'districts', 'thanas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division'    => 'required|string|max:255',
            'division_id' => 'nullable|integer|exists:divisions,id',
            'district'    => 'required|string|max:255',
            'district_id' => 'nullable|integer|exists:districts,id',
            'thana'       => 'required|string|max:255',
        ]);

        $divisionId   = $request->input('division_id');
        $divisionName = trim($request->input('division'));

        $districtId   = $request->input('district_id');
        $districtName = trim($request->input('district'));
        $thanaName    = trim($request->input('thana'));

        try {
            if ($divisionId) {
                $division = Division::findOrFail($divisionId);
            } else {
                $division = Division::firstOrCreate([
                    'name' => $divisionName,
                ]);
            }

            if ($districtId) {
                $district = District::findOrFail($districtId);
            } else {
                $district = District::firstOrCreate([
                    'name'        => $districtName,
                    'division_id' => $division->id
                ]);
            }

            $existingThana = Thana::whereName($thanaName)
                ->where('district_id', $district->id)
                ->first();

            if ($existingThana) {
                return redirect()->route('admin.location.index')->with('error', 'This Thana already exists in this District.');
            }

            Thana::create([
                'name'        => $thanaName,
                'district_id' => $district->id,
            ]);

            return redirect()->route('admin.location.index')->with('success', 'Location saved successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.location.index')->with('error', 'Failed to save location: ' . $e->getMessage());
        }
    }

    public function updateDivision(Request $request, Division $division)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'name.unique' => 'The name already exists.',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.location.index')->withErrors($validator)->withInput();
        }
        $divisionName = $request->input('name');
        try {
            $division->update(['name' => $divisionName]);
            return redirect()->route('admin.location.index')->with('success', 'Division updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.location.index')->with('error', 'Failed to update division.');
        }
    }

    public function updateDistrict(Request $request, District $district)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:districts,name,' . $district->id,
            'division_id' => 'required|integer|exists:divisions,id',
        ], [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'name.unique' => 'The name already exists.',
            'division_id.required' => 'Division field is required.',
            'division_id.integer' => 'Division ID must be an integer.',
            'division_id.exists' => 'Selected division does not exist.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.location.index')->withErrors($validator)->withInput();
        }

        $districtName = $request->input('name');
        $divisionId = $request->input('division_id');
        try {
            $district->update(['name' => $districtName, 'division_id' => $divisionId]);
            return redirect()->route('admin.location.index')->with('success', 'District updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.location.index')->with('error', 'Failed to update district.');
        }
    }

    public function updateThana(Request $request, Thana $thana)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('thanas', 'name')
                    ->where('district_id', $request->input('district_id'))
                    ->ignore($thana->id)
            ],
            'district_id' => 'required|integer|exists:districts,id',
        ], [
            'name.required'         => 'Name is required.',
            'name.string'           => 'Name must be a string.',
            'name.max'              => 'Name cannot exceed 255 characters.',
            'name.unique'           => 'The Thana name already exists in this District.',
            'district_id.required'  => 'District field is required.',
            'district_id.integer'   => 'District ID must be an integer.',
            'district_id.exists'    => 'Selected district does not exist.',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();

            return redirect()->route('admin.location.index')
                ->with('error', $firstError)
                ->withInput();
        }

        try {
            $thana->update([
                'name'        => trim($request->input('name')),
                'district_id' => $request->input('district_id'),
            ]);

            return redirect()->route('admin.location.index')->with('success', 'Thana updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.location.index')->with('error', 'Failed to update thana.');
        }
    }

    public function destroyDivision(Division $division)
    {
        try {
            $division->delete($division->id);
            return redirect()->route('admin.location.index')->with('success', 'Division deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.location.index')->with('error', 'Failed to delete division.');
        }
    }

    public function destroyDistrict(District $district)
    {
        try {
            $district->delete($district->id);
            return redirect()->route('admin.location.index')->with('success', 'District deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.location.index')->with('error', 'Failed to delete district.');
        }
    }

    public function destroyThana(Thana $thana)
    {
        try {
            $thana->delete($thana->id);
            return redirect()->route('admin.location.index')->with('success', 'Thana deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.location.index')->with('error', 'Failed to delete thana.');
        }
    }

    public function destroy()
    {
        // Logic to delete a location
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
