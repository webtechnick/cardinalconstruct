<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdminOfNewReview
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReviewCreated  $event
     * @return void
     */
    public function handle(ReviewCreated $event)
    {
        $review = $event->review;

        if ($review->isNegative()) {
            // Send out email to encourage response.
        } else {
            // Send out email to encourage approval.
        }

    }
}
