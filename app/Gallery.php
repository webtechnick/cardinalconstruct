<?php

namespace App;

use App\Photo;
use App\Traits\Models\Sluggable;
use App\Traits\Models\ToggleActivatable;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Gallery extends Model
{
    use Sluggable;
    use ToggleActivatable;

    protected $fillable = ['title','body','slug'];

    /**
     * A gallery has many photos
     * @return Relationship
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
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
     * Find a gallery by it's slug
     * @param  string $slug
     * @return Gallery
     */
    public static function findBySlug($slug)
    {
        return self::where(['slug' => $slug])->with('photos')->first();
    }

    /**
     * Constraint to only show active photos unless you're an admin
     * @return [type] [description]
     */
    public static function findAllSorted()
    {
        return self::with(['photos' => function ($query) {
            // Only add constrait if we're not admin or worker
            if (Auth::check()) {
                if (Auth::user()->isAdmin() || Auth::user()->isWorker()) {
                    return;
                }
            }
            $query->active();
        }])->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * URl to Gallery
     * @return String url relative
     */
    public function url()
    {
        return '/gallery/' . $this->slug;
    }

    /**
     * Add a photo to the gallery
     * @param Photo $photo
     */
    public function addPhoto(Photo $photo)
    {
        return $this->photos()->save($photo);
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

    /**
     * Delete all the photos attached to a gallery first
     * @return bool success
     */
    public function delete()
    {
        $photos = $this->photos()->select('id')->get();
        foreach ($photos as $photo) {
            $photo->delete();
        }
        return parent::delete();
    }
}
