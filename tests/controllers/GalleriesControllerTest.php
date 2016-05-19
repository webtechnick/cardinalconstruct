<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GalleriesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_show_a_login_form()
    {
        $this->visit('gallery/create');
        $this->assertEquals(env('APP_URL') . '/login', $this->currentUri);
    }

    /** @test */
    public function it_should_see_a_create_form() {
        $user = factory(App\User::class)->create();
        $this->actingAs($user);

        $this->visit('gallery/create')->see('Create your gallery');
    }

    /** @test */
    public function it_should_show_galleries() {
        $this->visit('gallery');
        $this->assertViewHas('galleries');
        $this->assertEquals(env('APP_URL') . '/gallery', $this->currentUri);
    }
}
