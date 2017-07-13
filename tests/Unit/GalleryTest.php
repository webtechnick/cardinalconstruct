<?php

namespace Tests\Unit;

use App\Gallery;
use App\Photo;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test  */
    public function it_should_delete_photos_when_deleted()
    {
        $gallery = factory(Gallery::class)->create();
        $gallery->addPhoto(factory(Photo::class)->make());

        $photo = $gallery->photos()->first();

        $this->assertTrue(Photo::where('id', $photo->id)->exists());
        $this->assertCount(1, $gallery->photos);

        $gallery->delete();

        $this->assertFalse(Photo::where('id', $photo->id)->exists());
        $this->assertFalse(Gallery::where('id', $gallery->id)->exists());
    }

    /** @test */
    public function it_should_have_an_owner()
    {
        $gallery = factory(Gallery::class)->create();
        $user = factory(User::class)->create(['id' => 999]);

        $this->assertFalse($gallery->ownedBy($user));

        $gallery->user_id = $user->id;
        $gallery->save();

        $this->assertTrue($gallery->ownedBy($user));
    }

    /** @test */
    public function it_should_have_a_url()
    {
        $gallery = factory(Gallery::class)->create(['title' => 'some slug']);

        $this->assertEquals('/gallery/some-slug', $gallery->url());
    }

    /** @test */
    public function it_should_be_able_to_be_found_by_slug()
    {
        $gallery = factory(Gallery::class)->create(['slug' => 'some-slug']);

        $gallery2 = Gallery::findBySlug($gallery->slug);

        $this->assertEquals($gallery->title, $gallery2->title);
        $this->assertEquals($gallery->body, $gallery2->body);
        $this->assertEquals($gallery->id, $gallery2->id);
    }

    /** @test */
    public function it_should_slug_automatically()
    {
        $gallery = factory(Gallery::class)->create(['title' => 'A title']);

        $this->assertEquals('a-title', $gallery->slug);

        $gallery->title = 'A Title'; // Just capital change
        $gallery->save();

        $this->assertEquals('a-title', $gallery->slug);

        $gallery->title = 'a new title';
        $gallery->save();

        $this->assertEquals('a-new-title', $gallery->slug);
    }

    /** @test */
    public function it_should_itterate_slug_if_same_title()
    {
        $gallery = factory(Gallery::class)->create(['title' => 'A title']);
        $this->assertEquals('a-title', $gallery->slug);

        $gallery = factory(Gallery::class)->create(['title' => 'A title']);
        $this->assertEquals('a-title-1', $gallery->slug);
    }

    /** @test */
    public function it_should_filter_inactive_photos_for_non_admin()
    {
        $gallery = factory(Gallery::class)->create();
        $gallery->addPhoto(factory(Photo::class)->make());
        $gallery->addPhoto(factory(Photo::class)->make(['is_active' => false]));

        $galleries = Gallery::findAllSorted();

        $this->assertCount(1, $galleries[0]->photos);
        $this->assertTrue($galleries[0]->photos[0]->isActive());
    }

    /** @test */
    public function it_should_not_filter_if_admin()
    {
        $gallery = factory(Gallery::class)->create();
        $gallery->addPhoto(factory(Photo::class)->make());
        $gallery->addPhoto(factory(Photo::class)->make(['is_active' => false]));
        $user = factory(User::class)->create(['role' => 'admin']);

        $this->actingAs($user);

        $galleries = Gallery::findAllSorted();

        $this->assertCount(2, $galleries[0]->photos);
    }
}
