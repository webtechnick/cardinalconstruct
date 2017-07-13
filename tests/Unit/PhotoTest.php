<?php

namespace Tests\Unit;

use App\Libs\Thumbnail;
use App\Photo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Tests\TestCase;
use Mockery as m;

class PhotoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_should_be_created_from_fileupload()
    {
        $file = m::mock(UploadedFile::class, [
            'getClientOriginalName' => 'foo.jpg',
        ]);

        $file->shouldReceive('move')->once();

        $image = m::mock(Thumbnail::class, [
            'save' => true
        ]);

        $count = Photo::count();

        Image::shouldReceive('make->fit->save')
               ->once();

        $photo = Photo::fromFileUpload($file);

        $this->assertContains('foo.jpg', $photo->name);
        $this->assertContains('uploads/photos', $photo->path);
        $this->assertContains('foo.jpg', $photo->path);
        $this->assertContains('uploads/photos/tn-', $photo->thumbnail_path);
        $this->assertContains('foo.jpg', $photo->thumbnail_path);
        $this->assertEquals(Photo::count(), $count); // Make sure we didn't actually save it.
    }

    /** @test */
    public function it_should_set_path_when_updating_name() {
        $photo = new Photo(['name' => 'foo.jpg']);

        $this->assertEquals('foo.jpg', $photo->name);
        $this->assertEquals('uploads/photos/foo.jpg', $photo->path);
        $this->assertEquals('uploads/photos/tn-foo.jpg', $photo->thumbnail_path);
    }


    /** @test */
    public function it_should_toggle_active() {
        $photo = factory(Photo::class)->create();

        $this->assertTrue($photo->isActive());
        $photo->toggleActive();
        $this->assertFalse($photo->isActive());
        $photo->toggleActive();
        $this->assertTrue($photo->isActive());
    }
}