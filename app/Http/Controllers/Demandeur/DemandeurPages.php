<?php

namespace App\Http\Controllers\Demandeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DemandeurPages extends Controller
{
    public function contact(){
        return view('demandeur.pages.contact');
    }

    public function about(){
        return view('demandeur.pages.about');
    }

    public function service(){
        return view('demandeur.pages.service');
    }
}
