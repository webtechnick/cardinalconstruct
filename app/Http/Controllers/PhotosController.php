<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Http\Requests;
use App\Http\Requests\AddPhotoRequest;
use App\Http\Requests\ModifyPhotoRequest;
use App\Photo;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    /**
     * Add a photo to the gallery
     * this is an ajax request.
     * @param string          $slug
     * @param AddPhotoRequest $request
     * @return  string done
     */
    public function store($slug, AddPhotoRequest $request)
    {
        $photo = Photo::fromFileUpload($request->file('photo'));
        Gallery::findBySlug($slug)->addPhoto($photo);

        return 'Done';
    }

    /**
     * Delete the photo
     * @param  Photo  $photo [description]
     * @return redirect
     */
    public function destroy(Photo $photo, ModifyPhotoRequest $request)
    {
        $photo->delete();

        flash()->success('Photo Deleted.');
        return back();
    }

    public function toggle(Photo $photo, ModifyPhotoRequest $request) {
        $photo->toggleActive();

        flash()->success('Photo Toggled.');
        return back();
    }
}
