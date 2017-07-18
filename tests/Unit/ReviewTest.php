<?php

namespace Tests\Unit;

use App\Review;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_should_have_average_rating()
    {
        $review1 = $this->create('App\Review', ['rating' => 1, 'is_active' => true]);
        $review2 = $this->create('App\Review', ['rating' => 4, 'is_active' => true]);
        $review3 = $this->create('App\Review', ['rating' => 5, 'is_active' => true]);
        $review3 = $this->create('App\Review', ['rating' => 5, 'is_active' => true]);
        $review3 = $this->create('App\Review', ['rating' => 5, 'is_active' => true]);

        $this->assertEquals(4, Review::averageRating());
    }

    /** @test */
    public function it_can_be_activated()
    {
        $review = $this->create('App\Review');

        $this->assertFalse($review->isActive());

        $review->activate()->save();

        $this->assertTrue($review->isActive());
    }

    /** @test */
    public function it_can_be_deactivated()
    {
        $review = $this->create('App\Review');

        $this->assertFalse($review->isActive());

        $review->activate()->save();

        $this->assertTrue($review->isActive());

        $review->deactivate()->save();

        $this->assertFalse($review->isActive());
    }

    /** @test */
    public function it_can_have_a_tone()
    {
        $review = $this->make('App\Review', ['rating' => 1]);
        $this->assertEquals($review->tone(), -1);

        $review = $this->make('App\Review', ['rating' => 2]);
        $this->assertEquals($review->tone(), 0);

        $review = $this->make('App\Review', ['rating' => 3]);
        $this->assertEquals($review->tone(), 0);

        $review = $this->make('App\Review', ['rating' => 4]);
        $this->assertEquals($review->tone(), 1);

        $review = $this->make('App\Review', ['rating' => 5]);
        $this->assertEquals($review->tone(), 1);
    }

    /** @test */
    public function it_can_be_negative()
    {
        $review = $this->make('App\Review', ['rating' => 1]);
        $this->assertTrue($review->isNegative());
    }

    /** @test */
    public function it_can_be_neutral()
    {
        $review = $this->make('App\Review', ['rating' => 3]);
        $this->assertTrue($review->isNeutral());
    }

    /** @test */
    public function it_can_be_positive()
    {
        $review = $this->make('App\Review', ['rating' => 5]);
        $this->assertTrue($review->isPositive());
    }

    /** @test */
    public function it_will_have_a_color_class_based_on_tone()
    {
        $review = $this->make('App\Review', ['rating' => 1]);
        $this->assertEquals($review->panelClass(), 'panel-danger');

        $review = $this->make('App\Review', ['rating' => 3]);
        $this->assertEquals($review->panelClass(), 'panel-default');

        $review = $this->make('App\Review', ['rating' => 5]);
        $this->assertEquals($review->panelClass(), 'panel-success');
    }
}
