<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{

    protected $fillable = ['subject_id', 'day', 'start_time', 'end_time','date', 'venue'];

    public function subject()
	{
		return $this->belongsTo(Subject::class, 'subject_id');
	}
}
