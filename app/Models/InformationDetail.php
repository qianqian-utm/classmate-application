<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformationDetail extends Model
{
	protected $fillable = ['title', 'description', 'type', 'assignment_type', 'exam_type', 'venue', 'scheduled_at','subject_id'];

	protected $casts = [
		'scheduled_at' => 'datetime',
	];

	public function subject()
	{
		return $this->belongsTo(Subject::class, 'subject_id');
	}

	// Scopes for easy filtering
	public function scopeByType($query, $type)
	{
		return $query->where('type', $type);
	}
}
