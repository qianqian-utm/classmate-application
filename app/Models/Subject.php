<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //
    protected $fillable = ['name', 'code'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_subject');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'class_list', 'subject_id', 'user_id');
    }

    public function lecturers()
    {
        return $this->belongsToMany(User::class, 'class_list', 'subject_id', 'user_id')
            ->where('users.role', 2);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_list', 'subject_id', 'user_id')
            ->where('users.role', 3);
    }
}
