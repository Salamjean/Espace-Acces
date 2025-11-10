<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentPointage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentPointageController extends Controller
{
   public function pointerArrivee()
    {
        // Récupérer l'agent connecté avec le guard sanctum
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        $aujourdhui = now()->toDateString();

        // Vérifier si un pointage existe déjà pour aujourd'hui
        $pointage = AgentPointage::where('agent_id', $agent->id)
                                ->whereDate('date_pointage', $aujourdhui)
                                ->first();

        if ($pointage) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà pointé votre arrivée aujourd\'hui.'
            ], 400);
        }

        // Créer un nouveau pointage
        AgentPointage::create([
            'agent_id' => $agent->id,
            'date_pointage' => $aujourdhui,
            'heure_arrivee' => now(),
            'statut' => $this->determinerStatut()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Arrivée pointée avec succès à ' . now()->format('H:i'),
            'heure_arrivee' => now()->format('H:i'),
            'statut' => $this->determinerStatut()
        ]);
    }

    public function pointerDepart()
    {
        // Récupérer l'agent connecté avec le guard sanctum
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        $aujourdhui = now()->toDateString();

        // Récupérer le pointage du jour
        $pointage = AgentPointage::where('agent_id', $agent->id)
                                ->whereDate('date_pointage', $aujourdhui)
                                ->first();

        if (!$pointage) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez pointer votre arrivée avant de pointer le départ.'
            ], 400);
        }

        if ($pointage->heure_depart) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà pointé votre départ aujourd\'hui.'
            ], 400);
        }

        // Mettre à jour l'heure de départ
        $pointage->update([
            'heure_depart' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Départ pointé avec succès à ' . now()->format('H:i'),
            'heure_depart' => now()->format('H:i'),
            'duree_travail' => $this->calculerDureeTravail($pointage->heure_arrivee, now())
        ]);
    }

    public function getStatutPointage()
    {
        // Récupérer l'agent connecté avec le guard sanctum
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        $aujourdhui = now()->toDateString();

        $pointage = AgentPointage::where('agent_id', $agent->id)
                                ->whereDate('date_pointage', $aujourdhui)
                                ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'a_pointé_arrivee' => $pointage && $pointage->heure_arrivee,
                'a_pointé_depart' => $pointage && $pointage->heure_depart,
                'heure_arrivee' => $pointage && $pointage->heure_arrivee 
                                ? $pointage->heure_arrivee->format('H:i') 
                                : null,
                'heure_depart' => $pointage && $pointage->heure_depart 
                                ? $pointage->heure_depart->format('H:i') 
                                : null,
                'statut' => $pointage ? $pointage->statut : 'non_pointé',
                'date_pointage' => $aujourdhui
            ]
        ]);
    }

    private function determinerStatut()
    {
        $heureLimite = now()->setHour(8)->setMinute(0); // 8h00
        return now()->gt($heureLimite) ? 'en_retard' : 'present';
    }

    private function calculerDureeTravail($heureArrivee, $heureDepart)
    {
        $diff = $heureArrivee->diff($heureDepart);
        return $diff->format('%Hh%I');
    }
}