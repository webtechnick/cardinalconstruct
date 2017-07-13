<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Http\Requests;
use App\Http\Requests\AddPhotoRequest;
use App\Http\Requests\GalleryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleriesController extends Controller
{

    /**
     * require user to be logged in, exepct for show and index
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'show',
            'index'
        ]]);
    }

    /**
     * Index of the gallery
     * @return View
     */
    public function index()
    {
        return view('galleries.index', ['galleries' => Gallery::findAllSorted()]);
    }

    /**
     * Create view for
     * @return View
     */
    public function create()
    {
        return view('galleries.create');
    }

    /**
     * Create a new Gallery
     * @param  GalleryRequest $request [description]
     * @return redirect
     */
    public function store(GalleryRequest $request)
    {
        $gallery = Auth::user()->addGallery(
            new Gallery($request->all())
        );

        flash()->success('Gallery successfully saved.');

        return redirect($gallery->url());
    }

    /**
     * Edit view for a gallery
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function edit(Gallery $gallery)
    {
        if (empty($gallery)) {
            flash()->error('No gallery found.');
            return back();
        }
        return view('galleries.edit', compact('gallery'));
    }

    /**
     * Update a gallery
     * @param  Gallery $gallery
     * @param  GalleryRequest $request
     * @return [type] [description]
     */
    public function update(Gallery $gallery, GalleryRequest $request)
    {
        if (empty($gallery)) {
            flash()->error('No Gallery Found');
        }
        $gallery->update($request->all());
        flash()->success('Gallery Updated!');

        return redirect($gallery->url());
    }

    /**
     * Show a gallery
     * @param  Gallery $gallery
     * @return View
     */
    public function show(Gallery $gallery)
    {
        if (empty($gallery)) {
            flash()->error('Gallery not found.');
            return redirect('/gallery');
        }
        return view('galleries.show', compact('gallery'));
    }

    /**
     * Destroy the gallery and it's photos.
     * @param  Gallery        $gallery [description]
     * @param  GalleryRequest $request [description]
     * @return redirect back
     */
    public function destroy(Gallery $gallery, GalleryRequest $request)
    {
        if (empty($gallery)) {
            flash()->error('Unable to find gallery.');
            return back();
        }

        $gallery->delete();
        flash()->success('Gallery Deleted.');
        return back();
    }
}
