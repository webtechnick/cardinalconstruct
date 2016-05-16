<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Photo extends Model
{
	protected $fillable = ['path','is_active'];

	protected $baseDir = 'galleries/photos/';

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public static function fromForm(UploadedFile $file) {
    	$photo = new static;
    	$file_name = time() . '-' . $file->getClientOriginalName();
    	$photo->path = $photo->baseDir . $file_name;
    	$file->move($photo->baseDir, $file_name);
    	return $photo;
    }
}
