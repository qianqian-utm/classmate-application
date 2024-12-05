<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        // Search by code, name, or lecturer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhereHas('users', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                            ->where('role', 2); // Only lecturers
                });
            });
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('groups.id', $request->group);
            });
        }

        $subjects = $query->with(['groups', 'users'])->paginate(10);
        $groups = Group::all(); // To populate group filter dropdown

        return view('subjects.index', compact('subjects', 'groups'));
    }

    public function create()
    {
        $groups = Group::all();
        $users = User::where('role', 2)->get(); // Only lecturers
        return view('subjects.create', compact('groups', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:subjects|max:20',
            'name' => 'required|max:255',
            'groups' => 'required|array|min:1',
            'users' => 'required|array|min:1'
        ], [
            'groups.required' => 'At least one group must be selected.',
            'groups.min' => 'At least one group must be selected.',
            'users.required' => 'At least one user must be selected.',
            'users.min' => 'At least one lecturer must be selected.'
        ]);

        // Additional check to ensure at least one selected user is a lecturer
        $lecturerExists = User::whereIn('id', $validated['users'])
            ->where('role', 2)
            ->exists();

        if (!$lecturerExists) {
            return back()->withInput()->withErrors([
                'users' => 'At least one selected user must be a lecturer.'
            ]);
        }

        $subject = Subject::create([
            'code' => $validated['code'],
            'name' => $validated['name']
        ]);

        $subject->groups()->attach($validated['groups']);
        $subject->users()->attach($validated['users']);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $groups = Group::all();
        // Filter users to only include lecturers and students
        $users = User::whereIn('role', [2, 3])->get();
        $selectedGroups = $subject->groups->pluck('id')->toArray();
        $selectedUsers = $subject->users->pluck('id')->toArray();
        
        return view('subjects.edit', compact('subject', 'groups', 'users', 'selectedGroups', 'selectedUsers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => 'required|unique:subjects,code,'.$subject->id.'|max:20',
            'name' => 'required|max:255',
            'groups' => 'required|array|min:1',
            'users' => 'required|array|min:1'
        ], [
            'groups.required' => 'At least one group must be selected.',
            'groups.min' => 'At least one group must be selected.',
            'users.required' => 'At least one user must be selected.',
            'users.min' => 'At least one lecturer must be selected.'
        ]);

        // Additional check to ensure at least one selected user is a lecturer
        $lecturerExists = User::whereIn('id', $validated['users'])
            ->where('role', 2)
            ->exists();

        if (!$lecturerExists) {
            return back()->withInput()->withErrors([
                'users' => 'At least one selected user must be a lecturer.'
            ]);
        }

        $subject->update([
            'code' => $validated['code'],
            'name' => $validated['name']
        ]);

        // Sync groups and users
        $subject->groups()->sync($validated['groups']);
        $subject->users()->sync($validated['users']);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->groups()->detach();
        $subject->users()->detach();
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}