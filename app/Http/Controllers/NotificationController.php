<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\InformationDetail;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch($user->role){
            case(1):
                $informationDetails = InformationDetail::with('subject')
                ->orderBy('scheduled_at', 'desc')
                ->get()
                ->groupBy('type');

                $subjects = Subject::all()->sortBy('name');
                break;
            case(2):
                $informationDetails = InformationDetail::whereHas('subject', function($query) use ($user) {
                    $query->whereHas('lecturers', function($subQuery) use ($user) {
                        $subQuery->where('users.id', $user->id);
                    });
                })
                ->orderBy('scheduled_at', 'desc')
                ->get()
                ->groupBy('type');

                $subjects = Subject::whereHas('lecturers', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->orderBy('name','asc')->get();
                break;
            case(3):
                // Fetch notifications for subjects the student is enrolled in
                $informationDetails = InformationDetail::whereHas('subject', function($query) use ($user) {
                    $query->whereHas('students', function($subQuery) use ($user) {
                        $subQuery->where('users.id', $user->id);
                    });
                })
                ->orderBy('scheduled_at', 'desc')
                ->get()
                ->groupBy('type');

                $subjects = Subject::whereHas('students', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->orderBy('name','asc')->get();
                break;
        }

        $notifications = [
            'class' => $informationDetails['class'] ?? [],
            'exam' => $informationDetails['exam'] ?? [],
            'assignment' => $informationDetails['assignment'] ?? []
        ];

        return view('notification.index', compact('notifications','subjects'));
    }

    public function createNotification($type)
    {
        $user       = Auth::user();
        $subjects   = Subject::all()->sortBy('name');
        $validTypes = ['class', 'assignment', 'exam'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        // Lecturer can create only for their subjects
        if ($user->role === 2) {
            $subjects = $subjects->filter(function ($subject) use ($user) {
                return $subject->lecturers->contains($user);
            });

            if ($subjects->isEmpty()) {
                abort(403, 'Unauthorized subject access');
            }
        }

        if ( ($user->role === 1) || ($user->role === 2) ) {
            return view('notification.create', compact('type','subjects'));
        }

        abort(403, 'Unauthorized access');
    }

    public function storeNotification(Request $request, $type)
    {
        $user       = Auth::user();
        $subjects   = Subject::all()->sortBy('name');
        $validTypes = ['class', 'assignment', 'exam'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'venue' => 'nullable|string',
            'scheduled_at' => 'required|date'
        ];

        // Add type-specific validation
        if ($type === 'assignment') {
            $validationRules['assignment_type'] = 'required|in:lab,individual,group_project';
        } elseif ($type === 'exam') {
            $validationRules['exam_type'] = 'required|in:quiz,midterm,final';
        }

        $validatedData = $request->validate($validationRules);
        $validatedData['type'] = $type;

        // Lecturer can only create for their subjects
        if ($user->role === 2) {
            $subjects = $subjects->filter(function ($subject) use ($user) {
                return $subject->lecturers->contains($user);
            });

            if ($subjects->isEmpty()) {
                abort(403, 'Unauthorized subject access');
            }
        }

        if ( ($user->role === 1) || ($user->role === 2) ) {
            InformationDetail::create($validatedData);

            return redirect()->route("notification.index", ['type' => $type])
            ->with('success', ucfirst($type) . ' notification created successfully');
        }

        abort(403, 'Unauthorized access');
    }

    public function editNotification($type, $id)
    {
        $user       = Auth::user();
        $subjects   = Subject::all()->sortBy('name');
        $validTypes = ['class', 'assignment', 'exam'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $notification = InformationDetail::byType($type)->findOrFail($id);
        $subjects = Subject::all()->sortBy('name');

        // Admin can edit for any subject
        if ($user->role === 1) {
            return view('notification.edit', compact('type', 'notification', 'subjects'));
        }
        
        // Lecturer can create only for their subjects
        if ($user->role === 2) {
            $subjects = $subjects->filter(function ($subject) use ($user) {
                return $subject->lecturers->contains($user);
            });

            if ($subjects->isEmpty()) {
                abort(403, 'Unauthorized subject access');
            }

            return view('notification.edit', compact('type', 'notification', 'subjects'));
        }

        abort(403, 'Unauthorized access');
    }

    public function updateNotification(Request $request, $type, $id)
    {
        $validTypes = ['class', 'assignment', 'exam'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $notification = InformationDetail::byType($type)->findOrFail($id);

        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'venue' => 'nullable|string',
            'scheduled_at' => 'required|date'
        ];

        // Add type-specific validation
        if ($type === 'assignment') {
            $validationRules['assignment_type'] = 'required|in:lab,individual,group_project';
        } elseif ($type === 'exam') {
            $validationRules['exam_type'] = 'required|in:quiz,midterm,final';
        }

        $validatedData = $request->validate($validationRules);

        $notification->update($validatedData);

        return redirect()->route("notification.index", ['type' => $type])
            ->with('success', ucfirst($type) . ' notification updated successfully');
    }

    public function deleteNotification($type, $id)
    {
        $validTypes = ['class', 'assignment', 'exam'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $notification = InformationDetail::byType($type)->findOrFail($id);
        $notification->delete();

        return redirect()->route("notification.index", ['type' => $type])
            ->with('success', ucfirst($type) . ' notification deleted successfully');
    }

}


