<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Mail\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function dycwindows()
    {
        return view('pages.dycwindows');
    }

    public function marvin()
    {
    	return view('pages.marvin');
    }

    public function simonton()
    {
    	return view('pages.simonton');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
    	return view('pages.contact');
    }

    public function send(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'type' => 'required',
            'body' => 'required',
        ]);

        Mail::send(new ContactUs($request->all()));

        flash()->success('Request sent, you will hear from one of our represenatives soon.');
        return redirect()->route('home');
    }
}
