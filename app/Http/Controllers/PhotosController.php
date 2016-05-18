<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Gallery;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\AddPhotoRequest;

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
}
