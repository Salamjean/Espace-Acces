<?php

namespace App\Http\Controllers\Admin\Demande;

use App\Http\Controllers\Controller;
use App\Mail\DemandeApprouvee;
use App\Models\Demande;
use App\Models\PersonneDemande;
use App\Notifications\DemandeApprouveeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminDemandeController extends Controller
{
    public function index(Request $request)
{
   // Récupérer le paramètre per_page avec une valeur par défaut de 20
    $perPage = $request->get('per_page', 8);
    
    // Récupérer toutes les personnes (pas seulement les demandeurs principaux)
    // EXCLURE les demandes de type permanent
    $query = PersonneDemande::with('demandeur')
        ->where('type_visiteur', '!=', 'permanent')
        ->orderBy('created_at', 'desc');

    // Appliquer les filtres (votre code existant reste le même)
    if ($request->has('statut') && $request->statut) {
        $query->where('statut', $request->statut);
    }

    if ($request->has('date_debut') && $request->date_debut) {
        $query->whereDate('date_visite', '>=', $request->date_debut);
    }

    if ($request->has('date_fin') && $request->date_fin) {
        $query->whereDate('date_visite', '<=', $request->date_fin);
    }

    if ($request->has('structure') && $request->structure) {
        $query->where('structure', 'like', "%{$request->structure}%");
    }

    if ($request->has('groupe_id') && $request->groupe_id) {
        $query->where('groupe_id', $request->groupe_id);
    }

    if ($request->has('type_demande') && $request->type_demande) {
        if ($request->type_demande == 'individuelle') {
            $query->where('est_demandeur_principal', true)
                ->where('nbre_perso', 1);
        } elseif ($request->type_demande == 'groupe') {
            $query->where('nbre_perso', '>', 1);
        }
    }

    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
            ->orWhere('prenom', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('numero_demande', 'like', "%{$search}%")
            ->orWhere('motif_visite', 'like', "%{$search}%")
            ->orWhere('contact', 'like', "%{$search}%")
            ->orWhere('structure', 'like', "%{$search}%")
            ->orWhere('groupe_id', 'like', "%{$search}%");
        });
    }

    // Utiliser la pagination avec le nombre d'éléments par page
    $personnesDemandes = $query->paginate($perPage)->appends($request->query());

    // Récupérer les structures uniques pour le filtre (en excluant aussi les permanents)
    $structures = PersonneDemande::whereNotNull('structure')
        ->where('structure', '!=', '')
        ->where('type_visiteur', '!=', 'permanent') // Exclure les permanents
        ->distinct()
        ->pluck('structure')
        ->sort();

    // Récupérer les groupes uniques pour le filtre (en excluant aussi les permanents)
    $groupes = PersonneDemande::whereNotNull('groupe_id')
        ->where('type_visiteur', '!=', 'permanent') // Exclure les permanents
        ->distinct()
        ->pluck('groupe_id')
        ->sort();

    // Statistiques basées sur toutes les personnes (en excluant les permanents)
    $totalPersonnes = PersonneDemande::where('type_visiteur', '!=', 'permanent')->count();
    $personnesEnAttente = PersonneDemande::where('statut', 'en_attente')
        ->where('type_visiteur', '!=', 'permanent')
        ->count();
    $personnesApprouvees = PersonneDemande::where('statut', 'approuve')
        ->where('type_visiteur', '!=', 'permanent')
        ->count();
    $personnesRejetees = PersonneDemande::where('statut', 'rejete')
        ->where('type_visiteur', '!=', 'permanent')
        ->count();
    $personnesTerminees = PersonneDemande::where('statut', 'termine')
        ->where('type_visiteur', '!=', 'permanent')
        ->count();
    $personnesAnnulees = PersonneDemande::where('statut', 'annule')
        ->where('type_visiteur', '!=', 'permanent')
        ->count();

    // Statistiques sur les types de demandes (en excluant les permanents)
    $demandesIndividuelles = PersonneDemande::where('est_demandeur_principal', true)
        ->where('nbre_perso', 1)
        ->where('type_visiteur', '!=', 'permanent')
        ->count();
    $demandesGroupe = PersonneDemande::where('nbre_perso', '>', 1)
        ->where('type_visiteur', '!=', 'permanent')
        ->count();

    return view('admin.demandes.index', compact(
        'personnesDemandes',
        'totalPersonnes',
        'personnesEnAttente',
        'personnesApprouvees',
        'personnesRejetees',
        'personnesTerminees',
        'personnesAnnulees',
        'demandesIndividuelles',
        'demandesGroupe',
        'structures',
        'groupes'
    ));
}

    public function show($id)
    {
        // Récupérer la personne demande avec les relations
        $personne = PersonneDemande::with(['demandeur'])->findOrFail($id);
        
        // Marquer comme lu si c'est un admin
        if (Auth::guard('admin')->check() && !$personne->is_read) {
            $personne->update(['is_read' => true]);
        }

        // Récupérer toutes les personnes du même groupe si c'est une demande groupée
        $personnesGroupe = [];
        if ($personne->nbre_perso > 1) {
            $personnesGroupe = PersonneDemande::where('groupe_id', $personne->groupe_id)
                ->where('id', '!=', $personne->id)
                ->get();
        }

        if (request()->ajax()) {
            $html = view('admin.demandes.partials.demande-details', compact('personne', 'personnesGroupe'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.demandes.show', compact('personne', 'personnesGroupe'));
    }

   public function approve(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $personne = PersonneDemande::findOrFail($id);
        
        // Vérifier si la demande peut être approuvée
        if ($personne->statut !== 'en_attente') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande ne peut pas être approuvée.'
            ], 400);
        }

        // Générer un code d'accès unique pour cette personne
        $codeAcces = $this->generateCodeAcces();
        
        // Générer le QR code avec les informations de la demande
        $qrCodePath = $this->generateQrCode($codeAcces, $personne);
        
        if (!$qrCodePath) {
            throw new \Exception("Échec de la génération du QR code");
        }

        // Mettre à jour la personne
        $personne->update([
            'statut' => 'approuve',
            'code_acces' => $codeAcces,
            'expiration_code_acces' => now()->addDays(7),
            'motif_rejet' => null,
            'path_qr_code' => $qrCodePath
        ]);

        // ENVOYER LA NOTIFICATION INDIVIDUELLE
        Notification::route('mail', $personne->email)
            ->notify(new DemandeApprouveeNotification($personne, $codeAcces, $qrCodePath));

        DB::commit();

        // RETOURNER UNE RÉPONSE JSON POUR AJAX
        return response()->json([
            'success' => true,
            'message' => $personne->prenom . ' ' . $personne->name . ' a été approuvé(e) avec succès. Email envoyé.',
            'statut' => 'approuve'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur approbation demande: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'approbation de la demande: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Générer un code d'accès unique
 */
private function generateCodeAcces()
{
    do {
        $code = strtoupper(Str::random(8));
    } while (PersonneDemande::where('code_acces', $code)->exists());

    return $code;
}

/**
 * Générer le QR code avec les informations de la demande
 */
/**
 * Générer le QR code avec seulement le code d'accès
 */
private function generateQrCode($codeAcces, $personne)
{
    try {
        // Le QR code contient seulement le code d'accès
        $qrContent = $codeAcces;
        
        Log::info('📋 Contenu QR code généré (code seulement): ' . $qrContent);
        
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($qrContent)
            ->size(300) // Taille réduite puisque moins d'infos
            ->margin(10)
            ->build();
        
        $fileName = 'qrcode_' . $personne->numero_demande . '_' . $personne->prenom . '_' . $personne->name . '_' . time() . '.png';
        $path = 'qrcodes/' . $fileName;
        
        Storage::disk('public')->put($path, $qrCode->getString());
        
        Log::info('✅ QR code généré avec code seulement: ' . $path);
        
        return $path;
        
    } catch (\Exception $e) {
        Log::error('Erreur génération QR code: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return null;
    }
}

   public function reject(Request $request, $id)
{
    $request->validate([
        'motif_rejet' => 'required|string|max:1000'
    ]);

    try {
        DB::beginTransaction();

        $demande = PersonneDemande::findOrFail($id);
        
        if ($demande->statut !== 'en_attente') {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande ne peut pas être rejetée.'
            ], 400);
        }

        $demande->update([
            'statut' => 'rejete',
            'agent_id' => Auth::id(),
            'motif_rejet' => $request->motif_rejet,
            'code_acces' => null,
            'expiration_code_acces' => null
        ]);

        DB::commit();

        // RETOURNER UNE RÉPONSE JSON
        return response()->json([
            'success' => true,
            'message' => 'Demande rejetée avec succès.',
            'statut' => 'rejete'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur rejet demande: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du rejet de la demande.'
        ], 500);
    }
}

   public function cancel(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $demande = PersonneDemande::findOrFail($id);
        
        if (!in_array($demande->statut, ['approuve', 'en_attente'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande ne peut pas être annulée.'
            ], 400);
        }

        $demande->update([
            'statut' => 'annule',
            'agent_id' => Auth::id()
        ]);

        DB::commit();

        // RETOURNER UNE RÉPONSE JSON
        return response()->json([
            'success' => true,
            'message' => 'Demande annulée avec succès.',
            'statut' => 'annule'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur annulation demande: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'annulation de la demande.'
        ], 500);
    }
}

  
}
