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

    public function send(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'type' => 'required',
            'body' => 'required',
        ]);

        //Mail::to(config('mail.from.address'))
        //
        flash()->success('Request sent, you will hear from one of our represenatives soon.');
        return redirect()->route('home');
    }
}
