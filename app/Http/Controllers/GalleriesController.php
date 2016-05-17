<?php

namespace App\Http\Controllers;

use App\Facades\Flash;
use App\Gallery;
use App\Http\Requests;
use App\Http\Requests\ChangeGalleryRequest;
use App\Http\Requests\GalleryRequest;
use App\Photo;
use Illuminate\Http\Request;

class GalleriesController extends Controller
{

    public function __construct() {
        $this->middleware('auth', ['except' => [
            'show',
            'index'
        ]]);
        parent::__construct();
    }
    public function index()
    {
        //Flash::success('This is a success message!');
        return view('galleries.index', ['galleries' => Gallery::all()]);
    }

    public function create()
    {
        return view('galleries.create');
    }

    public function store(GalleryRequest $request)
    {
        $gallery = $this->user->addGallery(
            new Gallery($request->all())
        );

        flash()->success('Gallery successfully saved.');

        return redirect($gallery->url());
    }

    public function show($slug)
    {
        $gallery = Gallery::findBySlug($slug);
        if (empty($gallery)) {
            flash()->error('Gallery not found.');
            return redirect('/gallery');
        }
        return view('galleries.show', compact('gallery'));
    }

    public function addPhoto($slug, ChangeGalleryRequest $request)
    {
        $photo = Photo::fromFileUpload($request->file('photo'));
        Gallery::findBySlug($slug)->addPhoto($photo);

        return 'Done';
    }
}
