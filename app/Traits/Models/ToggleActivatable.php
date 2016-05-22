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
            $this->deactivate();
            return $this->save();
        } else {
            $this->activate();
            return $this->save();
        }
    }

    /**
     * activate the record, without saving it.
     * @return self
     */
    public function activate()
    {
        $this->is_active = true;
        return $this;
    }

    /**
     * deactivate the record without saving it.
     * @return self
     */
    public function deactivate()
    {
        $this->is_active = false;
        return $this;
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
