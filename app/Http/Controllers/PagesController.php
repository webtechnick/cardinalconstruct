<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class PagesController extends Controller
{
    public function home()
    {
    	return view('pages.home');
    }

    public function remodel()
    {
    	return view('pages.remodel');
    }

    public function marvin()
    {
    	return view('pages.marvin');
    }

    public function simonton()
    {
    	return view('pages.simonton');
    }

    public function contact()
    {
    	return view('pages.contact');
    }
}
