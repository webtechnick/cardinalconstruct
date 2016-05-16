<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\Http\Requests;
use App\Http\Requests\GalleryRequest;

class GalleriesController extends Controller
{
    public function index()
    {
        return view('galleries.index', ['galleries' => Gallery::all()]);
    }

    public function create()
    {
        return view('galleries.create');
    }

    public function store(GalleryRequest $request)
    {
        $gallery = new Gallery($request->all());
        $gallery->user_id = 1; //Temp
        $gallery->save();

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
}
