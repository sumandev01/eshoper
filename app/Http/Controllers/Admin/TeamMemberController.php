<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::with('media')->orderBy('order', 'asc')->get();
        return view('dashboard.team-member.index', compact('teamMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:team_members,name',
            'position' => 'required|string|max:100',
            'main_thumb' => 'required',
        ]);

        $orderList = (TeamMember::max('order') ?? 0) + 1;

        try {
            $teamMember = TeamMember::create([
                'name' => $request->name,
                'position' => $request->position,
                'media_id' => $request->main_thumb,
                'order' => $orderList,
                'status' => 1,
            ]);

            return redirect()->route('admin.team-member.index')->with('success', 'Added successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.team-member.index')->with('error', 'Failed to add Team Member');
        }
    }

    public function edit(TeamMember $teamMember)
    {
        return view('dashboard.team-member.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:team_members,name,' . $teamMember->id,
            'position' => 'required|string|max:100',
            'main_thumb' => 'nullable',
            'media_id' => 'required|exists:media,id',
        ]);

        if ($request->has('main_thumb')) {
            $request->merge([
                'media_id' => $request->input('main_thumb'),
            ]);
        }
        try {
            $teamMember->update([
                'name' => $request->name,
                'position' => $request->position,
                'media_id' => $request->media_id,
            ]);

            return redirect()->route('admin.team-member.index')->with('success', 'Updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.team-member.index')->with('error', 'Failed to update Team Member');
        }
    }

    public function destroy(TeamMember $teamMember)
    {
        try {
            $teamMember->delete($teamMember->id);
            return redirect()->route('admin.team-member.index')->with('success', 'Deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.team-member.index')->with('error', 'Failed to delete Team Member');
        }
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            TeamMember::whereId($id)->update(['order' => $index + 1]);
        }
        return response()->json(['status' => 'success']);
    }
}
