<?php

namespace App\Observers;

use App\Models\InformationDetail;
use App\Models\ClassList;
use App\Mail\InformationDetailNotification;
use Illuminate\Support\Facades\Mail;

class InformationDetailObserver 
{
    public function created(InformationDetail $informationDetail)
    {
        $this->notifyStudents($informationDetail, 'created');
    }

    public function updated(InformationDetail $informationDetail)
    {
        $this->notifyStudents($informationDetail, 'updated');
    }

    private function notifyStudents(InformationDetail $informationDetail, string $action)
    {
        $students = $informationDetail->subject->students()
            ->whereHas('notificationPreferences', function ($query) use ($informationDetail) {
                $query->where('subject_id', $informationDetail->subject_id)
                    ->where('email_enabled', true);
            })
            ->get();

        foreach ($students as $student) {
            Mail::to($student->email)
                ->queue(new InformationDetailNotification($informationDetail, $action));
        }
    }
}