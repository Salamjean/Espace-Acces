<?php

namespace App\Http\Controllers\Demandeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandeurDashboard extends Controller
{
    public function dashboard(){
        return view('demandeur.dashboard');
    }

    public function logout(){
        Auth::guard('demandeur')->logout();
        return redirect()->route('demandeur.login');
    }
}
