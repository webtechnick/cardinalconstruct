<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait ToggleActivatable
{
    /**
     * check to see if active is created.
     * @return boolean [description]
     */
    public function isActive()
    {
        return !!$this->is_active;
    }

    /**
     * toggle active
     * @return self
     */
    public function toggleActive()
    {
        if ($this->isActive()) {
            $this->is_active = false;
            return $this->save();
        } else {
            $this->is_active = true;
            return $this->save();
        }
    }

    /**
     * active query scope
     * @param  Builder $query
     * @return Builder $query chain
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * unactive query scope
     * @param  Builder $query
     * @return Builder $query chain
     */
    public function scopeUnactive($query)
    {
        return $query->where('is_active', false);
    }
}
