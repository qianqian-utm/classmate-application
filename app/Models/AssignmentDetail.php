<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentDetail extends Model
{
    //
    protected $fillable = ['subject_id', 'title', 'description', 'due_date'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
