<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GalleryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test  */
    public function it_should_delete_photos_when_deleted()
    {
        $gallery = factory(App\Gallery::class)->create();
        $gallery->addPhoto(factory(App\Photo::class)->make());

        $photo = $gallery->photos()->first();

        $this->assertTrue(App\Photo::where('id', $photo->id)->exists());
        $this->assertCount(1, $gallery->photos);

        $gallery->delete();

        $this->assertFalse(App\Photo::where('id', $photo->id)->exists());
        $this->assertFalse(App\Gallery::where('id', $gallery->id)->exists());
    }
}
