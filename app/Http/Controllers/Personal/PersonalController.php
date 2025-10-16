<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\PersonneDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use PDF;

class PersonalController extends Controller
{
   /**
     * Afficher la liste des personnels permanents
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $statut = $request->get('statut', 'tous');
        
        $query = PersonneDemande::where('est_permanent', true)
            ->orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code_acces', 'like', "%{$search}%")
                  ->orWhere('numero_piece', 'like', "%{$search}%");
            });
        }
        
        if ($statut !== 'tous') {
            if ($statut === 'actif') {
                $query->where('date_fin_permanent', '>=', now())->where('statut', 'approuve');
            } elseif ($statut === 'expire') {
                $query->where('date_fin_permanent', '<', now());
            } else {
                $query->where('statut', $statut);
            }
        }
        
        $personnels = $query->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => PersonneDemande::where('est_permanent', true)->count(),
            'actif' => PersonneDemande::where('est_permanent', true)
                        ->where('date_fin_permanent', '>=', now())
                        ->where('statut', 'approuve')
                        ->count(),
            'expire' => PersonneDemande::where('est_permanent', true)
                        ->where('date_fin_permanent', '<', now())
                        ->count(),
            'en_attente' => PersonneDemande::where('est_permanent', true)
                            ->where('statut', 'en_attente')
                            ->count(),
        ];
        
        return view('admin.permanent-personnel.index', compact('personnels', 'stats', 'search', 'statut'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.permanent-personnel.create');
    }

    /**
     * Enregistrer un nouveau personnel permanent
     */
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:personne_demandes,email',
        'contact' => 'required|string|max:20',
        'fonction' => 'required|string|max:255',
        'structure' => 'required|string|max:255',
        'adresse' => 'required|string|max:500',
        'numero_piece' => 'required|string|max:50|unique:personne_demandes,numero_piece',
        'type_piece' => 'required|string|in:CNI,PASSEPORT,PERMIS,CARTE_SEJOUR',
        'photo_recto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'photo_verso' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Ajouté
        'motif_acces_permanent' => 'required|string|max:1000',
    ]);

    try {
        // Générer le code d'accès
        $codeAcces = PersonneDemande::generatePermanentCode();
        
        // Dates de validité (3 mois)
        $dateDebut = now();
        $dateFin = now()->addMonths(3);

        $personnel = new PersonneDemande();
        $personnel->name = $request->name;
        $personnel->prenom = $request->prenom;
        $personnel->email = $request->email;
        $personnel->contact = $request->contact;
        $personnel->fonction = $request->fonction;
        $personnel->structure = $request->structure;
        $personnel->adresse = $request->adresse;
        $personnel->numero_piece = $request->numero_piece;
        $personnel->type_piece = $request->type_piece;
        $personnel->code_acces = $codeAcces;
        $personnel->expiration_code_acces = $dateFin;
        $personnel->date_debut_permanent = $dateDebut;
        $personnel->date_fin_permanent = $dateFin;
        $personnel->motif_acces_permanent = $request->motif_acces_permanent;
        $personnel->est_permanent = true;
        $personnel->type_visiteur = 'permanent';
        $personnel->statut = 'approuve';
        
        // Champs obligatoires avec valeurs par défaut
        $personnel->date_visite = $dateDebut;
        $personnel->date_fin_visite = $dateFin;
        $personnel->heure_visite = '08:00';
        $personnel->motif_visite = 'Accès permanent personnel';
        $personnel->nbre_perso = 1;
        $personnel->numero_demande = 'PERM-' . time();
        $personnel->groupe_id = 'PERM-' . uniqid();
        $personnel->est_demandeur_principal = true;

        // Sauvegarder les photos
        if ($request->hasFile('photo_recto')) {
            $file = $request->file('photo_recto');
            $filename = 'recto_permanent_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('personnel/permanent', $filename, 'public');
            $personnel->photo_recto = $path;
        }

        if ($request->hasFile('photo_verso')) {
            $file = $request->file('photo_verso');
            $filename = 'verso_permanent_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('personnel/permanent', $filename, 'public');
            $personnel->photo_verso = $path;
        }

        // Sauvegarder la photo de profil
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_permanent_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('personnel/profiles', $filename, 'public');
            $personnel->profile_picture = $path;
        }

        $personnel->save();

        // Générer le QR Code avec la méthode qui fonctionne
        $qrPath = $this->generatePermanentQrCode($codeAcces, $personnel);
        
        if ($qrPath) {
            $personnel->path_qr_code = $qrPath;
            $personnel->save(); // Resauvegarder avec le chemin du QR code
        }

        return redirect()->route('admin.permanent-personnel.index')->with([
            'success' => 'Personnel permanent créé avec succès!',
            'code_acces' => $codeAcces,
            'personnel_id' => $personnel->id
        ]);

    } catch (\Exception $e) {
        // Log l'erreur pour debug
        Log::error('Erreur création personnel permanent: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        
        return redirect()->back()
            ->withErrors(['error' => 'Erreur lors de la création: ' . $e->getMessage()])
            ->withInput();
    }
}

private function generatePermanentQrCode($codeAcces, $personnel)
{
    try {
        // Le QR code contient seulement le code d'accès
        $qrContent = $codeAcces;
        
        Log::info('📋 Contenu QR code permanent généré: ' . $qrContent);
        
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($qrContent)
            ->size(300)
            ->margin(10)
            ->build();
        
        $fileName = 'qr_permanent_' . $personnel->id . '_' . time() . '.png';
        $path = 'qr-codes/permanent/' . $fileName;
        
        // Créer le dossier s'il n'existe pas
        Storage::disk('public')->makeDirectory('qr-codes/permanent');
        
        Storage::disk('public')->put($path, $qrCode->getString());
        
        Log::info('✅ QR code permanent généré: ' . $path);
        
        return $path;
        
    } catch (\Exception $e) {
        Log::error('Erreur génération QR code permanent: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return null;
    }
}

    /**
     * Générer et afficher la carte d'accès
     */
    public function generateCard($id)
    {
        $personnel = PersonneDemande::where('est_permanent', true)->findOrFail($id);
        
        return view('admin.permanent-personnel.access-card', compact('personnel'));
    }

    /**
     * Télécharger la carte d'accès PDF
     */
public function downloadCard($id)
{
    $personnel = PersonneDemande::findOrFail($id);
    
    // Convertir les images en base64 pour le PDF
    $profilePictureBase64 = null;
    $qrCodeBase64 = null;
    
    if ($personnel->profile_picture && Storage::disk('public')->exists($personnel->profile_picture)) {
        $profilePicturePath = Storage::disk('public')->path($personnel->profile_picture);
        $profilePictureData = base64_encode(file_get_contents($profilePicturePath));
        $profilePictureBase64 = 'data:image/jpeg;base64,' . $profilePictureData;
    }
    
    if ($personnel->path_qr_code && Storage::disk('public')->exists($personnel->path_qr_code)) {
        $qrCodePath = Storage::disk('public')->path($personnel->path_qr_code);
        $qrCodeData = base64_encode(file_get_contents($qrCodePath));
        $qrCodeBase64 = 'data:image/png;base64,' . $qrCodeData;
    }
    
    $pdf = PDF::loadView('admin.permanent-personnel.access-card-pdf', [
        'personnel' => $personnel,
        'profilePictureBase64' => $profilePictureBase64,
        'qrCodeBase64' => $qrCodeBase64
    ]);
    
    // Changement ici : format A4 portrait au lieu de A6 landscape
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('enable-local-file-access', true);
    $pdf->setOption('isHtml5ParserEnabled', true);
    $pdf->setOption('isRemoteEnabled', true);
    $pdf->setOption('dpi', 150);
    
    return $pdf->download('carte-acces-' . $personnel->name . '-' . $personnel->prenom . '.pdf');
}

    /**
     * Renouveler l'accès pour 3 mois supplémentaires
     */
    public function renew($id)
    {
        $personnel = PersonneDemande::where('est_permanent', true)->findOrFail($id);
        
        $personnel->expiration_code_acces = now()->addMonths(3);
        $personnel->date_fin_permanent = now()->addMonths(3);
        $personnel->date_fin_visite = now()->addMonths(3);
        $personnel->statut = 'approuve';
        $personnel->save();

        return redirect()->back()->with('success', 'Accès renouvelé pour 3 mois supplémentaires!');
    }

    /**
     * Désactiver un accès
     */
    public function desactivate($id)
    {
        $personnel = PersonneDemande::where('est_permanent', true)->findOrFail($id);
        
        $personnel->statut = 'rejete';
        $personnel->save();

        return redirect()->back()->with('success', 'Accès désactivé avec succès!');
    }

    /**
     * Réactiver un accès
     */
    public function activate($id)
    {
        $personnel = PersonneDemande::where('est_permanent', true)->findOrFail($id);
        
        // Si la date de fin est expirée, on prolonge de 3 mois
        if ($personnel->date_fin_permanent < now()) {
            $personnel->date_fin_permanent = now()->addMonths(3);
            $personnel->expiration_code_acces = now()->addMonths(3);
            $personnel->date_fin_visite = now()->addMonths(3);
        }
        
        $personnel->statut = 'approuve';
        $personnel->save();

        return redirect()->back()->with('success', 'Accès réactivé avec succès!');
    }

    /**
     * Vérifier un code d'accès (pour les agents)
     */
    public function checkCodeAccess(Request $request)
    {
        $request->validate([
            'code_acces' => 'required|string|max:20'
        ]);

        $personnel = PersonneDemande::where('code_acces', $request->code_acces)
            ->where('est_permanent', true)
            ->where('statut', 'approuve')
            ->where('date_fin_permanent', '>=', now())
            ->first();

        if (!$personnel) {
            return response()->json([
                'valid' => false,
                'message' => 'Code d\'accès invalide ou expiré.'
            ]);
        }

        return response()->json([
            'valid' => true,
            'personne' => [
                'id' => $personnel->id,
                'name' => $personnel->name,
                'prenom' => $personnel->prenom,
                'email' => $personnel->email,
                'contact' => $personnel->contact,
                'fonction' => $personnel->fonction,
                'structure' => $personnel->structure,
                'type' => 'permanent',
                'date_fin_acces' => $personnel->date_fin_permanent->format('d/m/Y')
            ]
        ]);
    }

    /**
     * Afficher les détails d'un personnel
     */
    public function show($id)
    {
        $personnel = PersonneDemande::where('est_permanent', true)->findOrFail($id);
        
        return view('admin.permanent-personnel.show', compact('personnel'));
    }
}
