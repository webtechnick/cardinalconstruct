<?php

namespace App;

use App\Photo;
use App\Traits\Models\Sluggable;
use App\User;
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

    /**
     * Gallery belongs to a User
     * @return Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this gallery is owned by the user passed in
     *
     * @param  User   $user [description]
     * @return boolean result
     */
    public function ownedBy(User $user)
    {
        return $this->user_id == $user->id;
    }
}
