<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Http\Requests;
use App\Http\Requests\AddPhotoRequest;
use App\Http\Requests\GalleryRequest;
use Illuminate\Http\Request;

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
        parent::__construct();
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
        $gallery = $this->user->addGallery(
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
    public function edit($slug)
    {
        $gallery = Gallery::findBySlug($slug);
        return view('galleries.edit', compact('gallery'));
    }

    /**
     * Update a gallery
     * @return [type] [description]
     */
    public function update($slug, GalleryRequest $request)
    {
        $gallery = Gallery::findBySlug($slug);
        if (empty($gallery)) {
            flash()->error('No Gallery Found');
        }
        $gallery->update($request->all());
        flash()->success('Gallery Updated!');

        return redirect($gallery->url());
    }

    /**
     * Show a gallery
     * @param  string $slug
     * @return View
     */
    public function show($slug)
    {
        $gallery = Gallery::findBySlug($slug);
        if (empty($gallery)) {
            flash()->error('Gallery not found.');
            return redirect('/gallery');
        }
        return view('galleries.show', compact('gallery'));
    }

    public function destroy($slug, GalleryRequest $request)
    {
        $gallery = Gallery::findBySlug($slug);
        if (empty($gallery)) {
            flash()->error('Unable to find gallery.');
            return back();
        }

        $blah = $gallery->delete();
        flash()->success('Gallery Deleted.');
        return back();
    }
}
