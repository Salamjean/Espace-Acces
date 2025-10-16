<?php

namespace App\Http\Controllers\Agent\Code;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentScan;
use App\Models\CodeAcces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgentCodeController extends Controller
{
     public function showScanner()
    {
        // Vérifier que l'utilisateur est un agent
        $agent = Auth::guard('agent')->user();
        if (!$agent || !$agent instanceof Agent) {
            return redirect()->route('agent.login')->with('error', 'Accès réservé aux agents');
        }

        return view('agent.scanner');
    }

 public function scannerAgent(Request $request)
{
    $request->validate([
        'qr_data' => 'required|string'
    ]);

    try {
        // Vérifier que l'utilisateur est un agent
        $agent = Auth::guard('agent')->user();
        if (!$agent || !$agent instanceof Agent) {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux agents'
            ], 403);
        }

        // Essayer de décoder le QR code comme JSON
        $qrData = json_decode($request->qr_data, true);
        $codeUnique = null;

        if ($qrData && isset($qrData['code'])) {
            // Format JSON : {"code": "CA_ABC123", "porte": "Porte Principale", ...}
            $codeUnique = $qrData['code'];
        } else {
            // Format simple : juste le code string "CA_ABC123"
            $codeUnique = $request->qr_data;
            
            // Essayer d'extraire le code si c'est une URL ou autre format
            if (preg_match('/[A-Z0-9_]{8,}/', $codeUnique, $matches)) {
                $codeUnique = $matches[0];
            }
        }

        if (!$codeUnique) {
            return response()->json([
                'success' => false,
                'message' => 'QR code invalide - format non reconnu'
            ], 400);
        }

        // Chercher le code d'accès
        $codeAcces = CodeAcces::where('code_unique', $codeUnique)->first();

        if (!$codeAcces) {
            // Enregistrer le scan invalide même si le code n'existe pas
            AgentScan::create([
                'agent_id' => $agent->id,
                'code_acces_id' => null,
                'type_scan' => 'inconnu',
                'nom_porte' => 'Inconnue',
                'code_unique_scanne' => $codeUnique,
                'heure_scan' => now(),
                'est_valide' => false,
                'raison_invalidite' => 'Code non trouvé dans la base'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Code non trouvé: ' . $codeUnique
            ], 404);
        }

        if (!$codeAcces->estValide()) {
            // Enregistrer le scan invalide
            AgentScan::create([
                'agent_id' => $agent->id,
                'code_acces_id' => $codeAcces->id,
                'type_scan' => $codeAcces->type,
                'nom_porte' => $codeAcces->nom_porte,
                'code_unique_scanne' => $codeUnique,
                'heure_scan' => now(),
                'est_valide' => false,
                'raison_invalidite' => 'Code expiré ou désactivé'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Code expiré ou désactivé: ' . $codeAcces->nom_porte
            ], 400);
        }

        // VÉRIFICATION MODIFIÉE : Seulement vérifier si ce code spécifique a déjà été scanné récemment
        $dernierScanCeCode = AgentScan::where('agent_id', $agent->id)
            ->where('code_unique_scanne', $codeUnique) // Même code
            ->where('est_valide', true)
            ->where('heure_scan', '>=', now()->subHours(2))
            ->latest('heure_scan')
            ->first();

        if ($dernierScanCeCode) {
            $prochainScan = $dernierScanCeCode->heure_scan->addHours(2);
            $tempsRestant = now()->diff($prochainScan);
            
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà scanné ce code récemment. Vous devez attendre 2 heures pour le rescanner.',
                'temps_attente' => $tempsRestant->format('%h heures %i minutes'),
                'prochain_scan' => $prochainScan->format('H:i'),
                'code_scanne' => $codeUnique
            ], 429);
        }


        // Enregistrer le scan valide
        $scan = AgentScan::create([
            'agent_id' => $agent->id,
            'code_acces_id' => $codeAcces->id,
            'type_scan' => $codeAcces->type,
            'nom_porte' => $codeAcces->nom_porte,
            'code_unique_scanne' => $codeUnique,
            'heure_scan' => now(),
            'est_valide' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scan enregistré avec succès',
            'data' => [
                'porte' => $codeAcces->nom_porte,
                'type' => $codeAcces->type,
                'type_display' => $codeAcces->type == 'entree' ? 'Entrée' : 'Sortie',
                'heure' => now()->format('H:i:s'),
                'date' => now()->format('d/m/Y'),
                'agent' => $agent->name . ' ' . $agent->prenom,
                'prochain_scan_ce_code' => now()->addHours(2)->format('H:i'),
                'scan_id' => $scan->id,
                'code_unique' => $codeUnique,
                'message_info' => 'Vous pouvez scanner d\'autres codes immédiatement, mais ce code pourra être rescanné dans 2 heures'
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Erreur scan agent: ' . $e->getMessage(), [
            'agent_id' => $agent->id ?? 'non connecté',
            'qr_data' => $request->qr_data
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du scan: ' . $e->getMessage()
        ], 500);
    }
}

public function historiqueScansAgent(Request $request)
{
    $agent = Auth::guard('agent')->user();
    
    if (!$agent || !$agent instanceof Agent) {
        return redirect()->route('agent.dashboard')->with('error', 'Accès réservé aux agents');
    }

    // Commencer la requête avec les relations
    $query = $agent->scans()->with('codeAcces');

    // Appliquer les filtres
    if ($request->filled('type_scan')) {
        $query->where('type_scan', $request->type_scan);
    }

    if ($request->filled('est_valide')) {
        $query->where('est_valide', $request->est_valide);
    }

    if ($request->filled('date')) {
        $query->whereDate('heure_scan', $request->date);
    }

    // Calculer les statistiques AVANT la pagination
    $totalScans = $query->count();
    $totalEntrees = (clone $query)->where('type_scan', 'entree')->count();
    $totalSorties = (clone $query)->where('type_scan', 'sortie')->count();
    $totalValides = (clone $query)->where('est_valide', true)->count();

    // Récupérer les scans avec pagination
    $scans = $query->latest('heure_scan')->paginate(10);

    // Ajouter les paramètres de filtres à la pagination
    $scans->appends($request->except('page'));

    // Retourner la vue avec les données
    return view('agent.historique_scan', compact('scans', 'totalScans', 'totalEntrees', 'totalSorties', 'totalValides'));
}

    public function statutScanAgent()
    {
        $agent = Auth::guard('agent')->user();
        
        if (!$agent || !$agent instanceof Agent) {
            return response()->json([
                'success' => false,
                'message' => 'Accès réservé aux agents'
            ], 403);
        }

        $scansRecents = $agent->scans()
            ->where('est_valide', true)
            ->where('heure_scan', '>=', now()->subHours(2))
            ->latest('heure_scan')
            ->get();

        // Calculer les codes qui ne peuvent pas être rescannés
        $codesEnAttente = [];
        foreach ($scansRecents as $scan) {
            $prochainScan = $scan->heure_scan->addHours(2);
            if (now()->lt($prochainScan)) {
                $tempsRestant = now()->diff($prochainScan);
                $codesEnAttente[$scan->code_unique_scanne] = [
                    'porte' => $scan->nom_porte,
                    'type' => $scan->type_scan,
                    'heure_scan' => $scan->heure_scan->format('H:i:s'),
                    'prochain_scan' => $prochainScan->format('H:i'),
                    'temps_restant' => $tempsRestant->format('%h heures %i minutes')
                ];
            }
        }

        $dernierScan = $scansRecents->first();

        return response()->json([
            'success' => true,
            'data' => [
                'peut_scanner_tout' => true, // Toujours true maintenant
                'codes_en_attente' => $codesEnAttente,
                'nombre_codes_bloques' => count($codesEnAttente),
                'dernier_scan' => $dernierScan ? [
                    'heure' => $dernierScan->heure_scan->format('H:i:s'),
                    'porte' => $dernierScan->nom_porte,
                    'type' => $dernierScan->type_scan,
                    'code' => $dernierScan->code_unique_scanne
                ] : null,
                'message' => count($codesEnAttente) > 0 
                    ? 'Vous pouvez scanner de nouveaux codes, mais certains codes sont temporairement indisponibles'
                    : 'Vous pouvez scanner tous les codes'
            ]
        ]);
    }
}
