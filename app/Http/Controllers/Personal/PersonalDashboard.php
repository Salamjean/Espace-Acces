<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalDashboard extends Controller
{
    public function dashboard(Request $request)
    {
        // Récupérer l'utilisateur connecté (personnel permanent)
        $user = Auth::guard('personal')->user();
        
        // Récupérer les paramètres de filtrage
        $date = $request->get('date', now()->format('Y-m-d'));
        $statut = $request->get('statut', 'tous');
        $search = $request->get('search', '');
        
        // Construire la requête pour les visites du personnel connecté
        $query = Visite::with(['personneDemande', 'agent'])
            ->where('personne_demande_id', $user->id) // Filtrer par le personnel connecté
            ->orderBy('created_at', 'desc');
        
        // Filtre par date
        if ($date) {
            $query->whereDate('created_at', $date);
        }
        
        // Filtre par statut
        if ($statut !== 'tous') {
            $query->where('statut', $statut);
        }
        
        // Filtre par recherche (numéro de pièce)
        if ($search) {
            $query->where('numero_piece', 'like', "%{$search}%");
        }
        
        $visites = $query->paginate(10);
        
        // Statistiques personnalisées pour le personnel
        $stats = [
            'total' => Visite::where('personne_demande_id', $user->id)->count(),
            'en_cours' => Visite::where('personne_demande_id', $user->id)->where('statut', 'en_cours')->count(),
            'termine' => Visite::where('personne_demande_id', $user->id)->where('statut', 'termine')->count(),
            'aujourdhui' => Visite::where('personne_demande_id', $user->id)
                                ->whereDate('created_at', now()->format('Y-m-d'))->count()
        ];
        
        return view('permanent-personnel.dashboard', compact('visites', 'stats', 'date', 'statut', 'search'));
    }

    public function logout(){
        Auth::guard('personal')->logout();
        return redirect()->route('personal.login');
    }
}
