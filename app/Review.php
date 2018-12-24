<?php

namespace App;

use App\Events\ReviewCreated;
use App\Traits\Models\ToggleActivatable;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use ToggleActivatable;

    protected $fillable = ['name', 'body', 'rating', 'response'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $events = [
        'created' => ReviewCreated::class,
    ];

    public static $ratings = [
        '5' => '5 (Excellent)',
        '4' => '4 (Above Average)',
        '3' => '3 (Average)',
        '2' => '2 (Below Average)',
        '1' => '1 (Poor)',
    ];

    /**
     * We have an average rating.
     * @return [type] [description]
     */
    public static function averageRating()
    {
        return round(self::active()->select('rating')->avg('rating'), 2);
    }

    /**
     * A review belongs to a user.
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Render the HTML stars.
     * @return [type] [description]
     */
    public function stars($rating = null)
    {
        return $rating ?: $this->rating;
    }

    /**
     * [panelClass description]
     * @return [type] [description]
     */
    public function panelClass()
    {
        switch ($this->tone())
        {
            case 1: return 'panel-success';
            case 0: return 'panel-default';
            case -1: return 'panel-danger';
        }
    }

    public function rowClass()
    {
        return $this->isActive() ? 'success' : 'danger';
    }

    /**
     * This is a negative review
     * @return boolean [description]
     */
    public function isNegative()
    {
        return $this->tone() < 0;
    }

    /**
     * This is a positive review.
     * @return boolean [description]
     */
    public function isPositive()
    {
        return $this->tone() > 0;
    }

    /**
     * This is a neutral review.
     * @return boolean [description]
     */
    public function isNeutral()
    {
        return $this->tone() == 0;
    }

    /**
     * Return the tone of the review as an integer.
     * @return integer (1: positive, 0: neutral, -1: negative)
     */
    public function tone()
    {
        if ($this->rating >= 4) {
            return 1; // positive
        }
        if ($this->rating >= 2 && $this->rating < 4) {
            return 0; // neutral
        }

        return -1; //negative
    }


}
