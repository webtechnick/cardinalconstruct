<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
	protected $fillable = ['file','is_active'];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
