<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class Photo extends Model
{
    protected $fillable = ['name', 'thumbnail_path', 'path','is_active'];

    /**
     * A photo belongs to a gallery
     * @return [type] [description]
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * filling the fields based on a passined in filename
     *
     * @param  String filename
     * @return self
     */
    public function saveAs($filename)
    {
        $this->name = time() . '-' . $filename;
        return $this;
    }

    /**
     * Mutator, if name changes, also updated path and thumbnail_path
     *
     * @param string name
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->path = $this->baseDir() . $name;
        $this->thumbnail_path = $this->baseDir() . 'tn-' . $name;
    }

    /**
     * Constructor building uploaded file
     *
     * @param  UploadedFile $file [description]
     * @return self
     */
    public static function fromFileUpload(UploadedFile $file)
    {
        // Build the photo
        $photo = (new static)->saveAs($file->getClientOriginalName());
        // Move the uploaded file
        $file->move($photo->baseDir(), $photo->name);
        // Make a thumbnail
        $photo->makeThumbnail();

        return $photo;
    }

    /**
     * Make the thumbnail out of the photo
     *
     * @return [type] [description]
     */
    public function makeThumbnail($size = 200)
    {
        Image::make($this->path)
            ->fit($size)
            ->save($this->thumbnail_path);

        return $this;
    }

    /**
     * Base directory path relative to /public
     * @return String path relative to public directory
     */
    public function baseDir()
    {
        return 'uploads/photos/';
    }
}
