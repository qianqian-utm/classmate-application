<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    
    //
    protected $fillable = ['group_id', 'subject_id', 'day', 'start_time', 'end_time','date', 'venue'];

    public function group()
{
    return $this->belongsTo(Group::class);
}
}
