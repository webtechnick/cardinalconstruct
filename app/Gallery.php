<?php

namespace App;

use App\Photo;
use App\Traits\Models\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use Sluggable;

    protected $fillable = ['title','body','slug'];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public static function findBySlug($slug)
    {
        return self::where(['slug' => $slug])->first();
    }

    public function url()
    {
        return '/gallery/' . $this->slug;
    }

    public function addPhoto(Photo $photo)
    {
    	return $this->photos()->save($photo);
    }
}
