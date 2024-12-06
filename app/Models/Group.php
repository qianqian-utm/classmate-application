<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'groups_subjects', 'group_id', 'subject_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}