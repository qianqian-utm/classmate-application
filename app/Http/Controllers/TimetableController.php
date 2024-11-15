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
        'start_time' => 'required',
        'end_time' => 'required',
        'date' => 'required',
        'venue' => 'required',
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



// public function update(Request $request, Timetable $timetable)
// {
//     $request->validate([
//         'group_id' => 'required|exists:groups,id',
//         'day' => 'required',
//         'start_time' => 'required',
//         'end_time' => 'required',
//         'venue' => 'nullable|string',
//     ]);

//     $timetable->update($request->all());

//     return redirect()->route('timetables.index')->with('success', 'Timetable updated successfully.');
// }

}
