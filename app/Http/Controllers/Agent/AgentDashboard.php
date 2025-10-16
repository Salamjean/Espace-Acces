<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentScan;
use App\Models\CodeAcces;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentDashboard extends Controller
{
public function dashboard(Request $request)
{
    $agent = Auth::guard('agent')->user();
    
    // Statistiques pour aujourd'hui
    $today = now()->format('Y-m-d');
    
    // Récupérer les scans récents de l'agent
    $scansRecents = AgentScan::where('agent_id', $agent->id)
        ->where('est_valide', true)
        ->where('heure_scan', '>=', now()->subHours(2))
        ->with('codeAcces')
        ->latest('heure_scan')
        ->get();

    // Calculer les codes qui ne peuvent pas être rescannés
    $codesEnAttente = [];
    $tempsPlusCourt = null;
    $tempsPlusCourtFormat = '00:00:00';

    foreach ($scansRecents as $scan) {
        $prochainScan = $scan->heure_scan->addHours(2);
        if (now()->lt($prochainScan)) {
            $tempsRestant = now()->diff($prochainScan);
            $totalSecondes = $tempsRestant->h * 3600 + $tempsRestant->i * 60 + $tempsRestant->s;
            
            $codesEnAttente[] = [
                'code' => $scan->code_unique_scanne,
                'porte' => $scan->nom_porte,
                'type' => $scan->type_scan,
                'heure_scan' => $scan->heure_scan,
                'prochain_scan' => $prochainScan,
                'temps_restant' => $tempsRestant,
                'temps_restant_format' => $tempsRestant->format('%h:%i:%s'),
                'total_secondes' => $totalSecondes
            ];

            // Trouver le temps le plus court
            if ($tempsPlusCourt === null || $totalSecondes < $tempsPlusCourt) {
                $tempsPlusCourt = $totalSecondes;
                $tempsPlusCourtFormat = $tempsRestant->format('%h:%i:%s');
            }
        }
    }

    $stats = [
        'total_entrees' => Visite::where('agent_id', $agent->id)
                                ->whereDate('date_entree', $today)
                                ->count(),
        
        'visiteurs_presence' => Visite::where('agent_id', $agent->id)
                                    ->where('statut', 'en_cours')
                                    ->count(),
        
        'total_sorties' => Visite::where('agent_id', $agent->id)
                                ->whereDate('date_sortie', $today)
                                ->count(),
        
        'codes_en_attente' => count($codesEnAttente),
        'liste_codes_attente' => $codesEnAttente,
        'temps_plus_court' => $tempsPlusCourtFormat,
        'a_des_codes_en_attente' => count($codesEnAttente) > 0,
        'scans_2h' => $scansRecents->count(),
        
        'total_visites' => Visite::where('agent_id', $agent->id)->count(),
        
        'visites_terminees' => Visite::where('agent_id', $agent->id)
                                ->where('statut', 'termine')
                                ->count(),

        'total_scans_aujourdhui' => AgentScan::where('agent_id', $agent->id)
                                        ->whereDate('heure_scan', $today)
                                        ->where('est_valide', true)
                                        ->count(),
        'total_codes_acces' => CodeAcces::where('est_actif', true)
                                ->where(function($query) {
                                    $query->whereNull('expire_at')
                                        ->orWhere('expire_at', '>', now());
                                })->count(),
    ];

    // Dernières visites (5 plus récentes)
    $recentVisites = Visite::with(['personneDemande'])
        ->where('agent_id', $agent->id)
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    // Derniers scans (pour l'historique)
    $recentScans = AgentScan::with('codeAcces')
        ->where('agent_id', $agent->id)
        ->where('est_valide', true)
        ->orderBy('heure_scan', 'desc')
        ->limit(5)
        ->get();

    return view('agent.dashboard', compact('agent', 'stats', 'recentVisites', 'recentScans'));
}

    /**
 * API pour les visites récentes (AJAX)
 */
public function recentVisites(Request $request)
{
    $agent = Auth::guard('agent')->user();
    
    $recentVisites = Visite::with(['personneDemande'])
        ->where('agent_id', $agent->id)
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    if ($request->ajax()) {
        $html = '';
        
        if ($recentVisites->count() > 0) {
            foreach ($recentVisites as $visite) {
                $html .= '
                <tr>
                    <td>
                        <div class="d-flex align-items-center" style="display:flex; justify-content:center">
                            <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                            <div>
                                <div class="fw-bold">' . $visite->personneDemande->prenom . ' ' . $visite->personneDemande->name . '</div>
                                <small class="text-muted">' . ($visite->personneDemande->fonction ?? 'Non renseigné') . '</small>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center">
                        <div class="fw-bold text-success">' . $visite->date_entree->format('H:i') . '</div>
                        <small class="text-muted">' . $visite->date_entree->format('d/m') . '</small>
                    </td>
                    <td style="text-align:center">';
                
                if ($visite->date_sortie) {
                    $html .= '
                        <div class="fw-bold text-danger">' . $visite->date_sortie->format('H:i') . '</div>
                        <small class="text-muted">' . $visite->date_sortie->format('d/m') . '</small>';
                } else {
                    $html .= '<span class="badge bg-warning">En attente</span>';
                }
                
                $html .= '
                    </td>
                    <td style="text-align:center">';
                
                if ($visite->statut == 'en_cours') {
                    $html .= '<span class="badge bg-warning"><i class="bi bi-clock me-1"></i>En cours</span>';
                } else {
                    $html .= '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Terminé</span>';
                }
                
                $html .= '
                    </td>
                    <td class="text-center">
                        <div class="btn-group">';
                
                if ($visite->statut == 'en_cours') {
                    $html .= '
                            <a href="' . route('visite.sortie') . '?code=' . $visite->personneDemande->code_acces . '" 
                               class="btn btn-sm btn-outline-danger"
                               data-bs-toggle="tooltip"
                               title="Enregistrer sortie">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>';
                }
                
                $html .= '
                        </div>
                    </td>
                </tr>';
            }
        } else {
            $html = '
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox me-2"></i>
                    Aucune visite enregistrée
                </td>
            </tr>';
        }
        
        return $html;
    }
    
    return response()->json(['error' => 'Unauthorized'], 401);
}

    public function logout(){
        Auth::guard('agent')->logout();
        return redirect()->route('agent.login');
    }
}
