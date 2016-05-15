<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $fillable = [
		'title','slug','body','publish_date'
	];

	public function user()
	{
		return $this->belongsTo(App\User::class);
	}

	public function comments()
	{
		return $this->hasMany(App\Comment::class);
	}
}
