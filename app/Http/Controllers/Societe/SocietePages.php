<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocietePages extends Controller
{
    public function contact(){
        return view('societe.pages.contact');
    }

    public function about(){
        return view('societe.pages.about');
    }

    public function service(){
        return view('societe.pages.service');
    }
}
