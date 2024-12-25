<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationPreference;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;

class NotificationPreferenceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $subjects = match($user->role) {
            1 => Subject::all(),
            2 => Subject::whereHas('lecturers', fn($query) => 
                $query->where('users.id', $user->id))->get(),
            3 => Subject::whereHas('students', fn($query) => 
                $query->where('users.id', $user->id))->get(),
            default => collect()
        };

        $preferences = $user->notificationPreferences()->pluck('email_enabled', 'subject_id');
        
        return view('notification-preferences.index', compact('subjects', 'preferences', 'user'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'global_email_enabled' => 'nullable|boolean',
            'preferences' => 'nullable|array',
            'preferences.*' => 'nullable|boolean'
        ]);

        $globalEnabled = isset($validated['global_email_enabled']);
        $preferences = $validated['preferences'] ?? [];

        $user = auth()->user();
        $user->update(['global_email_enabled' => $globalEnabled]);

        // Get accessible subjects
        $accessibleSubjects = match($user->role) {
            1 => Subject::pluck('id'),
            2 => Subject::whereHas('lecturers', fn($query) => 
                $query->where('users.id', $user->id))->pluck('id'),
            3 => Subject::whereHas('students', fn($query) => 
                $query->where('users.id', $user->id))->pluck('id'),
            default => collect()
        };

        // If global email is enabled, update each subject preference
        if ($globalEnabled) {
            foreach ($accessibleSubjects as $subjectId) {
                NotificationPreference::updateOrCreate(
                    ['user_id' => $user->id, 'subject_id' => $subjectId],
                    ['email_enabled' => isset($validated['preferences'][$subjectId]) ? true : false]
                );
            }
        } else {
            // If global email is disabled, disable all preferences
            NotificationPreference::where('user_id', $user->id)
                ->whereIn('subject_id', $accessibleSubjects)
                ->update(['email_enabled' => false]);
        }

        return redirect()->back()->with('success', 'Preferences updated successfully');
    }
}