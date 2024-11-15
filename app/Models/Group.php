<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    public function subjects()
{
    return $this->belongsToMany(Subject::class, 'group_subject');
}

public function timetables()
{
    return $this->hasMany(Timetable::class);
}
}
