<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationPreference;
use App\Models\Subject;

class NotificationPreferenceController extends Controller
{
    public function index()
    {
        $subjects = auth()->user()->role === 3 ? 
            Subject::whereHas('students', function($query) {
                $query->where('users.id', auth()->id());
            })->get() : 
            collect();

        $preferences = auth()->user()->notificationPreferences()->pluck('email_enabled', 'subject_id');

        return view('notification-preferences.index', compact('subjects', 'preferences'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'preferences' => 'required|array',
            'preferences.*' => 'boolean'
        ]);

        foreach ($validated['preferences'] as $subjectId => $enabled) {
            NotificationPreference::updateOrCreate(
                ['user_id' => auth()->id(), 'subject_id' => $subjectId],
                ['email_enabled' => $enabled]
            );
        }

        return redirect()->back()->with('success', 'Preferences updated successfully');
    }
}