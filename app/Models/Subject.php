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
}
