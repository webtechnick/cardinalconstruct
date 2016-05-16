<?php

namespace App\Traits\Models;

trait Sluggable
{
    public static function bootSluggable()
    {
        static::saving(function ($model) {
            $model->generateSlug();
        });
    }

    public function generateSlug()
    {
        if ($this->slug == '' && !empty($this->title)) {
            $this->slug = str_slug($this->title);
        }

        while ($count = self::where(['slug' => $this->slug])->count()) {
            $this->slug .= '-' . $count;
        }
    }
}
