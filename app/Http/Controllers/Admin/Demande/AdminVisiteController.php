<?php

namespace App\Http\Controllers\Admin\Demande;

use App\Http\Controllers\Controller;
use App\Models\Visite;
use Illuminate\Http\Request;

class AdminVisiteController extends Controller
{
     /**
     * Historiques des visites 
     */
    public function history(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $date = $request->get('date', now()->format('Y-m-d'));
        $statut = $request->get('statut', 'tous');
        $search = $request->get('search', '');
        
        // Construire la requête
        $query = Visite::with(['personneDemande', 'agent'])
            ->orderBy('created_at', 'desc');
        
        // Filtre par date
        if ($date) {
            $query->whereDate('created_at', $date);
        }
        
        // Filtre par statut
        if ($statut !== 'tous') {
            $query->where('statut', $statut);
        }
        
        // Filtre par recherche (nom, prénom, numéro de pièce)
        if ($search) {
            $query->whereHas('personneDemande', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('contact', 'like', "%{$search}%");
            })->orWhere('numero_piece', 'like', "%{$search}%");
        }
        
        $visites = $query->paginate(10);
        
        // Statistiques
        $stats = [
            'total' => Visite::count(),
            'en_cours' => Visite::where('statut', 'en_cours')->count(),
            'termine' => Visite::where('statut', 'termine')->count(),
            'aujourdhui' => Visite::whereDate('created_at', now()->format('Y-m-d'))->count()
        ];
        
        return view('admin.visite.history', compact('visites', 'stats', 'date', 'statut', 'search'));
    }
}
