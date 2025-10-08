<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LivraisonsExport;
use App\Http\Controllers\Controller;
use App\Models\Deces;
use App\Models\Mairie;
use App\Models\Mariage;
use App\Models\Naissance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminDashboard extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

}
