<?php

namespace App\Http\Controllers\Agent\Visite;

use App\Http\Controllers\Controller;
use App\Models\PersonneDemande;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SortieController extends Controller
{
    public function sortie()
    {
        return view('agent.visite.sortie');
    }

    /**
     * Vérifier le code de sortie
     */
    public function checkSortieCode(Request $request)
    {
        $request->validate([
            'code_acces' => 'required|string|max:20'
        ]);

        try {
            Log::info('Validation code sortie: ' . $request->code_acces);

            // Rechercher la personne par code d'accès
            $personne = PersonneDemande::where('code_acces', $request->code_acces)
                ->where('statut', 'approuve')
                ->first();

            if (!$personne) {
                Log::warning('Code sortie non trouvé: ' . $request->code_acces);
                return response()->json([
                    'valid' => false,
                    'message' => 'Code d\'accès invalide.'
                ]);
            }

            // Vérifier s'il y a une visite en cours pour cette personne
            $visiteEnCours = Visite::where('personne_demande_id', $personne->id)
                ->where('statut', 'en_cours')
                ->first();

            if (!$visiteEnCours) {
                Log::warning('Aucune visite en cours pour: ' . $personne->id);
                return response()->json([
                    'valid' => false,
                    'message' => 'Aucune visite en cours trouvée pour ce visiteur.'
                ]);
            }

            // Calculer la durée de visite
            $dureeVisite = $visiteEnCours->date_entree->diffInMinutes(now());

            return response()->json([
                'valid' => true,
                'personne' => [
                    'id' => $personne->id,
                    'name' => $personne->name,
                    'prenom' => $personne->prenom,
                    'email' => $personne->email,
                    'contact' => $personne->contact,
                    'fonction' => $personne->fonction,
                    'structure' => $personne->structure,
                    'heure_entree' => $visiteEnCours->date_entree->format('H:i'),
                    'date_entree' => $visiteEnCours->date_entree->format('d/m/Y'),
                    'duree_visite' => $this->formatDuree($dureeVisite),
                    'visite_id' => $visiteEnCours->id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur vérification code sortie: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'message' => 'Erreur serveur lors de la vérification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Formater la durée de visite
     */
    private function formatDuree($minutes)
    {
        if ($minutes < 60) {
            return $minutes . ' min';
        } else {
            $heures = floor($minutes / 60);
            $minutesRestantes = $minutes % 60;
            return $heures . 'h' . ($minutesRestantes > 0 ? $minutesRestantes . 'min' : '');
        }
    }

    /**
     * Enregistrer la sortie
     */
    public function storeSortie(Request $request)
    {
        $request->validate([
            'visite_id' => 'required|exists:visites,id',
            'personne_demande_id' => 'required|exists:personne_demandes,id'
        ]);

        try {
            DB::beginTransaction();

            // Récupérer la visite
            $visite = Visite::findOrFail($request->visite_id);

            // Vérifier que la visite est bien en cours
            if ($visite->statut !== 'en_cours') {
                return redirect()->back()->withErrors(['error' => 'Cette visite n\'est pas en cours.']);
            }

            // Mettre à jour la visite
            $visite->date_sortie = now();
            $visite->statut = 'termine';
            
            // Calculer la durée totale
            $duree = $visite->date_entree->diffInMinutes(now());
            $visite->duree_visite = $duree;

            $visite->save();

            DB::commit();

            // Rediriger vers la page de succès
            return redirect()->route('visite.sortie-success')->with([
                'success' => 'Sortie enregistrée avec succès!',
                'visiteur' => $visite->personneDemande->prenom . ' ' . $visite->personneDemande->nom,
                'heure_entree' => $visite->date_entree->format('H:i'),
                'heure_sortie' => now()->format('H:i'),
                'duree_visite' => $this->formatDuree($duree)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur enregistrement sortie: ' . $e->getMessage());
            
            return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'enregistrement de la sortie: ' . $e->getMessage()]);
        }
    }

    /**
     * Page de succès après sortie
     */
    public function sortieSuccess()
    {
        return view('agent.visite.sortie-success');
    }

   /**
     * Historiques des visites (pour l'agent connecté)
     */
    public function history(Request $request)
    {
        // Récupérer l'agent connecté
        $agent = Auth::guard('agent')->user();
        
        // Récupérer les paramètres de filtrage
        $date = $request->get('date', now()->format('Y-m-d'));
        $statut = $request->get('statut', 'tous');
        $search = $request->get('search', '');
        
        // Construire la requête uniquement pour l'agent connecté
        $query = Visite::with(['personneDemande', 'agent'])
            ->where('agent_id', $agent->id) // Filtrer par l'agent connecté
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
        
        $visites = $query->paginate(20);
        
        // Statistiques pour l'agent connecté uniquement
        $stats = [
            'total' => Visite::where('agent_id', $agent->id)->count(),
            'en_cours' => Visite::where('agent_id', $agent->id)->where('statut', 'en_cours')->count(),
            'termine' => Visite::where('agent_id', $agent->id)->where('statut', 'termine')->count(),
            'aujourdhui' => Visite::where('agent_id', $agent->id)->whereDate('created_at', now()->format('Y-m-d'))->count()
        ];
        
        return view('agent.visite.history', compact('visites', 'stats', 'date', 'statut', 'search', 'agent'));
    }
}
