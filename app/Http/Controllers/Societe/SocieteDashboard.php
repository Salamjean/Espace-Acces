<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocieteDashboard extends Controller
{
    public function dashboard(){
        return view('societe.dashboard');
    }

    public function logout(){
        Auth::guard('societe')->logout();
        return redirect()->route('society.login');
    }
}
