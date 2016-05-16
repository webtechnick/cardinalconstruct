<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GalleriesControllerTest extends TestCase
{
    /** @test */
    public function it_should_show_a_form()
    {
        $this->visit('gallery/create');
    }
}
