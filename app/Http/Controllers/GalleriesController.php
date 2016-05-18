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
        return view('galleries.index', ['galleries' => Gallery::all()]);
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
}
