<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	/**
	 * fillable variables
	 * @var [type]
	 */
    protected $fillable = [
        'body'
    ];

    public function user()
    {
        return $this->belongsTo(App\User::class);
    }

    public function post()
    {
        return $this->belongsTo(App\Post::class);
    }
}
