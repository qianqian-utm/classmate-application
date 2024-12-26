<?php

namespace App\Mail;

use App\Models\InformationDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformationDetailNotification extends Mailable
{
    public $informationDetail;
    public $action;

    public function __construct(InformationDetail $informationDetail, string $action)
    {
        $this->informationDetail = $informationDetail;
        $this->action = $action;
    }

    public function build()
    {
        return $this->markdown('emails.information-detail-notification')
            ->subject("{$this->informationDetail->subject->name}: New {$this->informationDetail->type}");
    }
}