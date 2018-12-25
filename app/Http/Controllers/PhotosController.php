<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Http\Requests;
use App\Http\Requests\AddPhotoRequest;
use App\Http\Requests\ModifyPhotoRequest;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotosController extends Controller
{
    /**
     * Add a photo to the gallery
     * this is an ajax request.
     * @param Gallery $gallery
     * @param AddPhotoRequest $request
     * @return  string done
     */
    public function store(Gallery $gallery, AddPhotoRequest $request)
    {
        $photo = Photo::fromFileUpload($request->file('photo'));
        if (! Auth::user()->isAdmin() ) { //If user is not admin, upload as deactivated.
            $photo->deactivate();
        }
        $gallery->addPhoto($photo);

        return 'Done';
    }

    /**
     * Add photo caption
     *
     * @param  Photo  $photo [description]
     * @return [type]        [description]
     */
    public function edit(Photo $photo)
    {
        return view('photos.edit', compact('photo'));
    }

    /**
     * [update description]
     * @param  Photo              $photo   [description]
     * @param  ModifyPhotoRequest $request [description]
     * @return [type]                      [description]
     */
    public function update(Photo $photo, ModifyPhotoRequest $request)
    {
        if (empty($photo)) {
            flash()->error('No photo found.');
            return back();
        }

        $photo->update($request->all());
        flash()->success('Photo updated!');

        return redirect($photo->gallery->url());
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

    /**
     * Toggle the photo
     *
     * @param  Photo              $photo   [description]
     * @param  ModifyPhotoRequest $request [description]
     * @return [type]                      [description]
     */
    public function toggle(Photo $photo, ModifyPhotoRequest $request) {
        $photo->toggleActive();

        flash()->success('Photo Toggled.');
        return back();
    }
}
