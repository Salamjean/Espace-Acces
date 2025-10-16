<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonneDemande;
use App\Models\Visite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Controller
{
    public function dashboard()
{
    // Récupérer les statistiques des demandes
    $totalPersonnes = PersonneDemande::where('type_visiteur', '!=', 'permanent')->count();
    $personnesEnAttente = PersonneDemande::where('statut', 'en_attente')->where('type_visiteur', '!=', 'permanent')->count();
    $personnesApprouvees = PersonneDemande::where('statut', 'approuve')->where('type_visiteur', '!=', 'permanent')->count();
    $personnesRejetees = PersonneDemande::where('statut', 'rejete')->where('type_visiteur', '!=', 'permanent')->count();
    $personnesTerminees = PersonneDemande::where('type_visiteur', '!=', 'permanent')->whereDate('date_fin_visite', '<', Carbon::today())->count();
    $personnesAnnulees = PersonneDemande::where('statut', 'annule')->where('type_visiteur', '!=', 'permanent')->count();

    // Statistiques sur les types de demandes
    $demandesIndividuelles = PersonneDemande::where('est_demandeur_principal', true)
        ->where('nbre_perso', 1)
        ->where('type_visiteur', '!=', 'permanent')->count();
    $demandesGroupe = PersonneDemande::where('nbre_perso', '>', 1)->where('type_visiteur', '!=', 'permanent')->count();

    // Statistiques des visites
    $visitesAujourdhui = Visite::whereDate('created_at', today())->count();
    $visitesTotal = Visite::count();

    return view('admin.dashboard', compact(
        'totalPersonnes',
        'personnesEnAttente',
        'personnesApprouvees',
        'personnesRejetees',
        'personnesTerminees',
        'personnesAnnulees',
        'demandesIndividuelles',
        'demandesGroupe',
        'visitesAujourdhui',
        'visitesTotal'
    ));
}

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

}
