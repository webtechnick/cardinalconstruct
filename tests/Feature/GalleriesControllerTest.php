<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GalleriesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_example()
    {
        $this->assertTrue(true);
    }

    /** @test */
    // public function it_should_show_a_login_form()
    // {
    //     $this->get('gallery/create');
    //     $this->assertEquals(env('APP_URL') . '/login', $this->currentUri);
    // }

    // /** @test */
    // public function it_should_see_a_create_form() {
    //     $user = factory(App\User::class)->create();
    //     $this->actingAs($user);

    //     $this->get('gallery/create')->see('Create your gallery');
    // }

    // /** @test */
    // public function it_should_show_galleries() {
    //     $this->get('gallery');
    //     $this->assertViewHas('galleries');
    //     $this->assertEquals(env('APP_URL') . '/gallery', $this->currentUri);
    // }
}
