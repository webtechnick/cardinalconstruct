<?php

namespace App\Traits\Models;

trait Sluggable
{
    /**
     * Boot and add saving event listener
     * @return void
     */
    public static function bootSluggable()
    {
        static::saving(function ($model) {
            $model->generateSlug();
        });
    }

    /**
     * Look at the title and slug it
     * @return void
     */
    public function generateSlug()
    {
        if ($this->slug != str_slug($this->title)) {
            $this->slug = str_slug($this->title);
        }

        while ($count = self::where(['slug' => $this->slug])->where('id', '!=', $this->id)->count()) {
            $this->slug .= '-' . $count;
        }
    }
}
