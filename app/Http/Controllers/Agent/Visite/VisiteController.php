<?php

namespace App\Http\Controllers\Agent\Visite;

use App\Http\Controllers\Controller;
use App\Models\PersonneDemande;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VisiteController extends Controller
{
    public function access(){
        return view('agent.visite.access');
    }

    public function create(){
        return view('agent.visite.create');
    }
    
     /**
     * Vérifier la validité du code d'accès
     */
public function checkCodeAccess(Request $request)
{
    $request->validate([
        'code_acces' => 'required|string|max:20'
    ]);

    try {
        Log::info('Validation code accès: ' . $request->code_acces);

        // Rechercher la personne par code d'accès
        $personne = PersonneDemande::where('code_acces', $request->code_acces)
            ->where('statut', 'approuve')
            ->first();

        if (!$personne) {
            Log::warning('Code non trouvé ou non approuvé: ' . $request->code_acces);
            return response()->json([
                'valid' => false,
                'message' => 'Code d\'accès invalide ou demande non approuvée.'
            ]);
        }

        // Vérifier l'expiration du code
        if ($personne->expiration_code_acces && $personne->expiration_code_acces < now()) {
            Log::warning('Code expiré: ' . $request->code_acces);
            return response()->json([
                'valid' => false,
                'message' => 'Le code d\'accès a expiré.'
            ]);
        }

        // Vérifier si une visite est déjà en cours pour cette personne
        $visiteEnCours = Visite::where('personne_demande_id', $personne->id)
            ->where('statut', 'en_cours')
            ->exists();

        if ($visiteEnCours) {
            Log::warning('Visite déjà en cours pour: ' . $personne->id);
            return response()->json([
                'valid' => false,
                'message' => 'Une visite est déjà en cours pour ce visiteur.'
            ]);
        }

        // Vérification de la période de validité
        $aujourdhui = now()->format('Y-m-d');
        $dateDebut = \Carbon\Carbon::parse($personne->date_visite)->format('Y-m-d');
        $dateFin = $personne->date_fin_visite ? \Carbon\Carbon::parse($personne->date_fin_visite)->format('Y-m-d') : $dateDebut;

        Log::info("Date aujourd'hui: " . $aujourdhui);
        Log::info("Date début visite: " . $dateDebut);
        Log::info("Date fin visite: " . $dateFin);

        if ($aujourdhui < $dateDebut || $aujourdhui > $dateFin) {
            Log::warning('Visite pas dans la période valide. Aujourd\'hui: ' . $aujourdhui . ', Période: ' . $dateDebut . ' à ' . $dateFin);
            
            $message = 'La visite n\'est pas prévue pour aujourd\'hui. ';
            if ($dateDebut == $dateFin) {
                $message .= 'Date prévue: ' . \Carbon\Carbon::parse($dateDebut)->format('d/m/Y');
            } else {
                $message .= 'Période valide: du ' . \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') . ' au ' . \Carbon\Carbon::parse($dateFin)->format('d/m/Y');
            }
            
            return response()->json([
                'valid' => false,
                'message' => $message
            ]);
        }

        // Vérification de l'heure de visite autorisée
        $heureActuelle = now()->format('H:i');
        $heureDebut = $personne->heure_visite;
        $heureFin = $personne->heure_fin_visite ?? null;

        if ($heureActuelle < $heureDebut) {
            return response()->json([
                'valid' => false,
                'message' => "Vous ne pouvez pas avoir accès. Votre heure prévue est: " . \Carbon\Carbon::parse($heureDebut)->format('H:i')
            ]);
        }

        if ($heureFin && $heureActuelle > $heureFin) {
            return response()->json([
                'valid' => false,
                'message' => "L'heure d'accès est dépassée. Heure limite : " . \Carbon\Carbon::parse($heureFin)->format('H:i')
            ]);
        }

        // Retourner les informations de la personne AVEC le groupe_id
        return response()->json([
            'valid' => true,
            'personne' => [
                'id' => $personne->id,
                'name' => $personne->name,
                'prenom' => $personne->prenom,
                'email' => $personne->email,
                'contact' => $personne->contact,
                'adresse' => $personne->adresse,
                'fonction' => $personne->fonction,
                'structure' => $personne->structure,
                'motif_visite' => $personne->motif_visite,
                'date_visite' => $personne->date_visite,
                'heure_visite' => $personne->heure_visite,
                'type_visiteur' => $personne->type_visiteur,
                'numero_piece' => $personne->numero_piece,
                'type_piece' => $personne->type_piece,
                'groupe_id' => $personne->groupe_id // AJOUT IMPORTANT
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Erreur vérification code accès: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'valid' => false,
            'message' => 'Erreur serveur lors de la vérification: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Enregistrer une nouvelle visite
     */
public function store(Request $request)
{
    $request->validate([
        'personne_demande_id' => 'required|exists:personne_demandes,id',
        'numero_carte' => 'required|string|max:20',
        'numero_piece' => 'nullable|string|max:50',
        'type_piece' => 'nullable|string|in:CNI,PASSEPORT,PERMIS,CARTE_SEJOUR',
        'photo_recto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'photo_verso' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'personnes_permanentes_associees' => 'nullable|string' // JSON string
    ]);

    try {
        DB::beginTransaction();

        $personne = PersonneDemande::findOrFail($request->personne_demande_id);

        // Vérifications existantes...
        $visiteEnCours = Visite::where('personne_demande_id', $personne->id)
            ->where('statut', 'en_cours')
            ->exists();

        if ($visiteEnCours) {
            return redirect()->back()->withErrors(['error' => 'Une visite est déjà en cours pour ce visiteur.']);
        }

        $carteUtilisee = Visite::where('numero_carte', $request->numero_carte)
            ->where('statut', 'en_cours')
            ->exists();

        if ($carteUtilisee) {
            return redirect()->back()->withErrors(['error' => 'Ce numéro de carte est déjà attribué à un visiteur en cours.']);
        }

        $visite = new Visite();
        $visite->personne_demande_id = $personne->id;
        $visite->agent_id = Auth::guard('agent')->user()->id;
        $visite->numero_carte = $request->numero_carte;
        $visite->date_entree = now();
        $visite->statut = 'en_cours';

        // CORRECTION : Stocker les personnes permanentes associées (JSON encodé)
        if ($request->has('personnes_permanentes_associees') && !empty($request->personnes_permanentes_associees)) {
            $personnesIds = json_decode($request->personnes_permanentes_associees, true);
            if (is_array($personnesIds) && !empty($personnesIds)) {
                // ENCODER le tableau en JSON avant de l'enregistrer
                $visite->personnes_permanentes_associees = json_encode($personnesIds);
            }
        }

        if ($personne->type_visiteur === 'permanent') {
            $visite->numero_piece = $personne->numero_piece;
            $visite->type_piece = $personne->type_piece;
            $visite->numero_carte = $personne->code_acces;
        } else {
            $visite->numero_piece = $request->numero_piece;
            $visite->type_piece = $request->type_piece;

            if ($request->hasFile('photo_recto')) {
                $filename = 'recto_' . time() . '_' . $personne->id . '.' . $request->file('photo_recto')->getClientOriginalExtension();
                $visite->photo_recto = $request->file('photo_recto')->storeAs('visites/pieces', $filename, 'public');
            }

            if ($request->hasFile('photo_verso')) {
                $filename = 'verso_' . time() . '_' . $personne->id . '.' . $request->file('photo_verso')->getClientOriginalExtension();
                $visite->photo_verso = $request->file('photo_verso')->storeAs('visites/pieces', $filename, 'public');
            }
        }

        $visite->save();

        DB::commit();

        return redirect()->route('visite.success')->with([
            'success' => 'Visiteur enregistré avec succès!',
            'visiteur' => $personne->prenom . ' ' . $personne->name,
            'numero_carte' => $visite->numero_carte,
            'numero_piece' => $visite->numero_piece,
            'type_visiteur' => $personne->type_visiteur,
            'personnes_permanentes' => $visite->personnes_permanentes_associees ?? []
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur enregistrement visite: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()])->withInput();
    }
}

    /**
     * Page de succès après enregistrement
     */
    public function success()
    {
        return view('agent.visite.visite-success');
    }

    public function getPersonnesPermanentes(Request $request)
    {
        try {
            Log::info('Recherche de TOUTES les personnes permanentes');

            // DEBUG: Compter le nombre total de personnes permanentes
            $totalPermanentes = PersonneDemande::where('type_visiteur', 'permanent')->count();
            $totalApprouvees = PersonneDemande::where('type_visiteur', 'permanent')
                ->where('statut', 'approuve')
                ->count();
            
            Log::info("Total personnes permanentes: " . $totalPermanentes);
            Log::info("Total personnes permanentes approuvées: " . $totalApprouvees);

            // Récupérer TOUTES les personnes permanentes sans filtre par groupe
            $personnes = PersonneDemande::where('type_visiteur', 'permanent')
                ->where('statut', 'approuve')
                ->where(function($query) {
                    $query->whereNull('expiration_code_acces')
                        ->orWhere('expiration_code_acces', '>', now());
                })
                ->select('id', 'name', 'prenom', 'fonction', 'contact', 'email')
                ->get();

            Log::info('Personnes permanentes trouvées après filtres: ' . $personnes->count());
            
            // Debug détaillé
            foreach ($personnes as $personne) {
                Log::info("Personne: " . $personne->prenom . " " . $personne->name . " - " . $personne->fonction);
            }

            return response()->json([
                'success' => true,
                'personnes' => $personnes,
                'debug' => [
                    'total_personnes_permanentes' => $personnes->count(),
                    'total_permanentes_bdd' => $totalPermanentes,
                    'total_approuvees' => $totalApprouvees,
                    'filtres_appliques' => [
                        'type_visiteur' => 'permanent',
                        'statut' => 'approuve',
                        'expiration' => 'non_expiree'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération personnes permanentes: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des personnes permanentes: ' . $e->getMessage(),
                'personnes' => []
            ], 500);
        }
    }
}
