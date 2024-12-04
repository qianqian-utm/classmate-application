<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\InformationDetail;

class NotificationController extends Controller
{
    public function notification()
    {
        $informationDetails = InformationDetail::with('subject')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type');

        $notifications = [
            'class' => $informationDetails['class'] ?? [],
            'exam' => $informationDetails['exam'] ?? [],
            'assignment' => $informationDetails['assignment'] ?? []
        ];

        return view('admin.notification', compact('notifications'));
    }

    public function listNotifications($type)
    {
        $validTypes = ['class', 'assignment', 'exam'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $notifications = InformationDetail::byType($type)->get();

        return view("admin.notification.{$type}", compact('notifications'));
    }

    public function createNotification($type)
    {
        $validTypes = ['class', 'assignment', 'exam'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $subjects = Subject::all(); // Fetch all subjects for dropdown

        return view('admin.notification.create', compact('type', 'subjects'));
    }

    public function storeNotification(Request $request, $type)
    {
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

        InformationDetail::create($validatedData);

        return redirect()->route("admin.notification", ['type' => $type])
            ->with('success', ucfirst($type) . ' notification created successfully');
    }

    public function editNotification($type, $id)
    {
        $validTypes = ['class', 'assignment', 'exam'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $notification = InformationDetail::byType($type)->findOrFail($id);
        $subjects = Subject::all(); // Fetch all subjects for dropdown

        return view('admin.notification.edit', compact('type', 'notification', 'subjects'));
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

        return redirect()->route("admin.notification", ['type' => $type])
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

        return redirect()->route("admin.notification", ['type' => $type])
            ->with('success', ucfirst($type) . ' notification deleted successfully');
    }


}


