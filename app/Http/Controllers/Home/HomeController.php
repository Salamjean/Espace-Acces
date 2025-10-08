<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        return view('home.accueil');
    }
    
    public function contact(){
        return view('home.pages.contact');
    }

    public function about(){
        return view('home.pages.about');
    }

    public function service(){
        return view('home.pages.service');
    }
}
