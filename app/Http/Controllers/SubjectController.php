<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
                            ->where('role', 2);
                });
            });
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('groups.id', $request->group);
            });
        }

        $subjects = $query->with(['groups', 'users'])
            ->orderBy('name', 'asc')
            ->paginate(10);

        $groups = Group::all();

        return view('subjects.index', compact('subjects', 'groups'));
    }

    public function create()
    {
        $groups    = Group::all();
        $lecturers = User::where('role', 2)->orderBy("name","asc")->get();
        $students  = User::where('role', 3)->orderBy("name","asc")->get();
        return view('subjects.create', compact('groups', 'lecturers', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:subjects|max:20',
            'name' => 'required|max:255',
            'groups' => 'required|array|min:1',
            'lecturers' => 'required|array|min:1',
            'students' => 'required|array|min:1'
        ], [
            'groups.required' => 'Group must be selected.',
            'lecturers.required' => 'At least one lecturer must be selected.',
            'students.required' => 'At least one student must be selected.'
        ]);

        $subject = Subject::create([
            'code' => $validated['code'],
            'name' => $validated['name']
        ]);

        // Combine lecturers and students for user sync
        $allUsers = array_merge($validated['lecturers'], $validated['students']);

        $subject->groups()->sync($validated['groups']);
        $subject->users()->sync($allUsers);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $groups    = Group::all();
        $lecturers = User::where('role', 2)->orderBy("name", "asc")->get();
        $students  = User::where('role', 3)->orderBy("name", "asc")->get();

        // prevent error when empty values
        $selectedGroups    = $subject->groups    ? $subject->groups->pluck('id')->toArray()    : [];
        $selectedLecturers = $subject->lecturers ? $subject->lecturers->pluck('id')->toArray() : [];
        $selectedStudents  = $subject->students  ? $subject->students->pluck('id')->toArray()  : [];
        return view('subjects.edit', compact('subject', 'groups', 'lecturers', 'students', 'selectedGroups', 'selectedLecturers', 'selectedStudents'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => 'required|unique:subjects,code,'.$subject->id.'|max:20',
            'name' => 'required|max:255',
            'groups' => 'required|array|min:1',
            'lecturers' => 'required|array|min:1',
            'students' => 'required|array|min:1'
        ], [
            'groups.required' => 'Group must be selected.',
            'lecturers.required' => 'At least one lecturer must be selected.',
            'students.required' => 'At least one student must be selected.'
        ]);

        $subject->update([
            'code' => $validated['code'],
            'name' => $validated['name']
        ]);

        $allUsers = array_merge($validated['lecturers'], $validated['students']);

        $subject->groups()->sync($validated['groups']);
        $subject->users()->sync($allUsers);

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

    public function show(Subject $subject, Request $request)
{
    $users = $subject->lecturers->merge($subject->students)->map(function ($user) {
        $user->role = $user->role === 2 ? 'lecturer' : 'student';
        return $user;
    })->sortBy('name');

    // Get filter parameters
    $searchTerm = $request->input('search', '');
    $roleFilter = $request->input('role', '');

    // Apply search filter
    if ($searchTerm) {
        $users = $users->filter(function ($user) use ($searchTerm) {
            return stripos($user->name, $searchTerm) !== false;
        });
    }

    // Apply role filter
    if ($roleFilter) {
        $users = $users->where('role', $roleFilter === '2' ? 'lecturer' : 'student');
    }

    $perPage = 10;
    $currentPage = $request->input('page', 1);
    $pagedUsers = new LengthAwarePaginator(
        $users->forPage($currentPage, $perPage),
        $users->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('subjects.show', [
        'subject' => $subject,
        'users' => $pagedUsers,
        'searchTerm' => $searchTerm,
        'roleFilter' => $roleFilter
    ]);
}
}