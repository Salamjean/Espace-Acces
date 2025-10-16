<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentPointage;
use App\Models\AgentScan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InfoController extends Controller
{
    public function info()
    {
        return view('admin.agent.presence');
    }

    public function getAgentStats(Request $request)
    {
        // Récupérer tous les agents avec leurs statistiques
        $agents = Agent::withCount([
            'scans as total_scans',
            'scans as scans_valides' => function($query) {
                $query->where('est_valide', true);
            },
            'scans as scans_invalides' => function($query) {
                $query->where('est_valide', false);
            },
            'pointages as total_pointages',
            'pointages as pointages_presents' => function($query) {
                $query->where('statut', 'present');
            },
            'pointages as pointages_retards' => function($query) {
                $query->where('statut', 'en_retard');
            }
        ])->get();

        return response()->json([
            'agents' => $agents,
            'stats_globales' => $this->getGlobalStats()
        ]);
    }

    public function getScansData(Request $request)
    {
        $query = AgentScan::with(['agent', 'codeAcces'])
            ->latest('heure_scan');

        // Filtres
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('type_scan')) {
            $query->where('type_scan', $request->type_scan);
        }

        if ($request->filled('est_valide')) {
            $query->where('est_valide', $request->est_valide);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('heure_scan', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('heure_scan', '<=', $request->date_fin);
        }

        $scans = $query->paginate(20);

        return response()->json([
            'scans' => $scans,
            'filters' => $request->all()
        ]);
    }

    public function getPointagesData(Request $request)
    {
        $query = AgentPointage::with('agent')
            ->latest('date_pointage');

        // Filtres
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_pointage', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_pointage', '<=', $request->date_fin);
        }

        $pointages = $query->paginate(20);

        return response()->json([
            'pointages' => $pointages,
            'filters' => $request->all()
        ]);
    }

    public function getDashboardStats()
    {
        $today = now()->toDateString();
        
        $stats = [
            'scans_aujourdhui' => AgentScan::whereDate('heure_scan', $today)->count(),
            'scans_valides_aujourdhui' => AgentScan::whereDate('heure_scan', $today)
                ->where('est_valide', true)->count(),
            'pointages_aujourdhui' => AgentPointage::whereDate('date_pointage', $today)->count(),
            'agents_presents' => AgentPointage::whereDate('date_pointage', $today)
                ->whereNotNull('heure_arrivee')
                ->whereNull('heure_depart')
                ->count(),
            'scans_7jours' => $this->getScansLast7Days(),
            'pointages_7jours' => $this->getPointagesLast7Days()
        ];

        return response()->json($stats);
    }

    private function getGlobalStats()
    {
        return [
            'total_scans' => AgentScan::count(),
            'scans_valides' => AgentScan::where('est_valide', true)->count(),
            'scans_invalides' => AgentScan::where('est_valide', false)->count(),
            'total_pointages' => AgentPointage::count(),
            'presents' => AgentPointage::where('statut', 'present')->count(),
            'retards' => AgentPointage::where('statut', 'en_retard')->count(),
            'agents_total' => Agent::count()
        ];
    }

    private function getScansLast7Days()
    {
        $dates = [];
        $scansData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d/m');
            $scansData[] = AgentScan::whereDate('heure_scan', $date)->count();
        }

        return [
            'labels' => $dates,
            'data' => $scansData
        ];
    }

    private function getPointagesLast7Days()
    {
        $dates = [];
        $pointagesData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d/m');
            $pointagesData[] = AgentPointage::whereDate('date_pointage', $date)->count();
        }

        return [
            'labels' => $dates,
            'data' => $pointagesData
        ];
    }
}