<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\Group;

class TimetableController extends Controller
{
    //
    // TimetableController.
    public function index()
    {
        $timetables = Timetable::with('group')->get();
        return view('Timetable/index', compact('timetables'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('Timetable/create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day' => 'required',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'date' => 'required',
            'venue' => 'nullable',
        ]);

        Timetable::create($request->all());

        return redirect()->route('tt.index')->with('success', 'Timetable created successfully.');
    }

    public function edit($id)
    {
        $groups = Group::all();
        $timetable = Timetable::find($id);
        return view('Timetable/edit', compact( 'groups', 'timetable'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
           'group_id' => 'required|exists:groups,id',
            'day' => 'required',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'date' => 'required',
            'venue' => 'nullable',
        ]);

        $timetable = Timetable::findOrFail($id);
        $timetable->update($request->all());

        return redirect()->route('tt.index')->with('success', 'Timetable updated successfully.');
    }

    public function delete($id)
    {
        $timetable = Timetable::findOrFail($id);
        if ($timetable) {
            $timetable->delete();
            return redirect()->route('tt.index')->with('success', 'Timetable deleted successfully.');
        }
        return redirect()->route('tt.index')->with('error', 'Failed to delete timetable.');
    }

}
