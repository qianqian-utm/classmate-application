<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassDetail extends Model
{
    //
    protected $fillable = ['subject_id', 'title', 'date', 'description'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
