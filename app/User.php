<?php

namespace App;

use App\Comment;
use App\Gallery;
use App\Post;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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

    public function addGallery(Gallery $gallery)
    {
        return $this->galleries()->save($gallery);
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }
}
