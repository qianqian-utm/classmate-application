<?php

namespace App\Observers;

use App\Models\InformationDetail;
use App\Models\ClassList;
use App\Models\User;
use App\Mail\InformationDetailNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InformationDetailObserver
{
    public function created(InformationDetail $informationDetail)
    {
        $this->notifyUsers($informationDetail, 'created');
    }

    public function updated(InformationDetail $informationDetail)
    {
        $this->notifyUsers($informationDetail, 'updated');
    }

    private function notifyUsers(InformationDetail $informationDetail, string $action)
    {
        $users = User::where('global_email_enabled', true)
            ->whereHas('notificationPreferences', function ($query) use ($informationDetail) {
                $query->where('subject_id', $informationDetail->subject_id)
                    ->where('email_enabled', true);
            })
            ->get();

        Log::info('Subscribed users:', $users->toArray());

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new InformationDetailNotification($informationDetail, $action));
        }
        Log::info('Sent email to users dragonfruit:', $users->toArray());
    }
}