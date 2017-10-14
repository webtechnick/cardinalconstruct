<?php

namespace App;

use App\Comment;
use App\Gallery;
use App\Post;
use App\Review;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    public static $roles = [
        'user' => 'user', // Default
        'admin' => 'admin', // Full admin rights
        'worker' => 'worker' // Can upload photos, but not approve them
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * A user has many reviews
     * @return [type] [description]
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function owns($model)
    {
        return $this->id == $model->user_id;
    }

    public function ownsOrIsAdmin($model)
    {
        return $this->isAdmin() || $this->owns($model);
    }

    public function ownsOrIsAdminOrWorker($model) {
        return $this->isWorker() || $this->ownsOrIsAdmin($model);
    }

    public function addGallery(Gallery $gallery)
    {
        return $this->galleries()->save($gallery);
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    /**
     * Is the current user a worker
     * @return boolean [description]
     */
    public function isWorker()
    {
        return $this->role == 'worker';
    }

    /**
     * Set the role of the user if we allow that role
     * @param string $role [description]
     */
    public function setRole($role = 'user')
    {
        if (in_array($role, self::$roles)) {
            $this->role = $role;
        }
        return $this;
    }
}
