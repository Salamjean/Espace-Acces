<?php

namespace App\Http\Controllers\Demande;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\PersonneDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DemandeController extends Controller
{
    public function list(){
        $userId = Auth::guard('demandeur')->user()->id;
        
         // Récupérer les paramètres de pagination
        $perPage = request('per_page', 10);
        
        // Construire la requête de base
        $query = PersonneDemande::where('demandeur_id', $userId);
        
        // Appliquer les filtres
        if (request('statut')) {
            $query->where('statut', request('statut'));
        }
        
        if (request('type_demande')) {
            if (request('type_demande') == 'individuelle') {
                $query->where('nbre_perso', 1);
            } elseif (request('type_demande') == 'groupe') {
                $query->where('nbre_perso', '>', 1);
            }
        }
        
        // Récupérer les demandes avec pagination
        $demandes = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage)
                        ->appends(request()->query());

        // Statistiques
        $totalDemandes = PersonneDemande::where('demandeur_id', $userId)->count();
        $demandesEnAttente = PersonneDemande::where('demandeur_id', $userId)->where('statut', 'en_attente')->count();
        $demandesApprouvees = PersonneDemande::where('demandeur_id', $userId)->where('statut', 'approuve')->count();
        $demandesRejetees = PersonneDemande::where('demandeur_id', $userId)->where('statut', 'rejete')->count();
        $demandesAnnulees = PersonneDemande::where('demandeur_id', $userId)->where('statut', 'annule')->count();

        return view('demandeur.demandes.list', compact(
            'demandes',
            'totalDemandes',
            'demandesEnAttente',
            'demandesApprouvees',
            'demandesRejetees',
            'demandesAnnulees'
        ));
    }

    public function create(){
        return view('demandeur.demandes.create');
    }

   public function store(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'type_demande' => 'required|in:moi,autres,moi_autres',
        'nbre_perso' => 'required|integer|min:1|max:10',
        'numero_ticket' => 'nullable|string|max:50',
        
        // Champs pour toutes les personnes avec leurs informations individuelles
        'personnes' => 'required|array|min:1',
        'personnes.*.name' => 'required|string|max:255',
        'personnes.*.prenom' => 'required|string|max:255',
        'personnes.*.contact' => 'required|string|max:20',
        'personnes.*.email' => 'required|email|max:255',
        'personnes.*.adresse' => 'required|string|max:255',
        'personnes.*.fonction' => 'required|string|max:255',
        'personnes.*.structure' => 'required|string|max:255',
        'personnes.*.profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        
        // Informations supplémentaires individuelles
        'personnes.*.date_visite' => 'required|date|after_or_equal:today',
        'personnes.*.date_fin_visite' => 'required|date|after_or_equal:personnes.*.date_visite',
        'personnes.*.heure_visite' => 'required|date_format:H:i',
        'personnes.*.heure_fin_visite' => 'nullable|date_format:H:i',
        'personnes.*.motif_visite' => 'required|string|max:255',
        'personnes.*.description_detaille' => 'nullable|string',
        
        // Informations véhicule individuelles (optionnelles)
        'personnes.*.marque_voiture' => 'nullable|string|max:255',
        'personnes.*.modele_voiture' => 'nullable|string|max:255',
        'personnes.*.immatriculation_voiture' => 'nullable|string|max:20',
        'personnes.*.type_intervention' => 'nullable|string|max:255',
        
        'documents_joints' => 'nullable|array',
        'documents_joints.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
    ], [
        'numero_ticket.max' => 'Le numéro de ticket ne doit pas dépasser 50 caractères.',
        'personnes.required' => 'Veuillez renseigner les informations des personnes.',
        'personnes.*.name.required' => 'Le nom est obligatoire pour chaque personne.',
        'personnes.*.prenom.required' => 'Le prénom est obligatoire pour chaque personne.',
        'personnes.*.contact.required' => 'Le contact est obligatoire pour chaque personne.',
        'personnes.*.email.required' => 'L\'email est obligatoire pour chaque personne.',
        'personnes.*.adresse.required' => 'L\'adresse est obligatoire pour chaque personne.',
        'personnes.*.fonction.required' => 'La fonction est obligatoire pour chaque personne.',
        'personnes.*.structure.required' => 'La structure est obligatoire pour chaque personne.',
        'personnes.*.date_visite.required' => 'La date de début est obligatoire pour chaque personne.',
        'personnes.*.date_fin_visite.required' => 'La date de fin est obligatoire pour chaque personne.',
        'personnes.*.heure_visite.required' => 'L\'heure de début est obligatoire pour chaque personne.',
        'personnes.*.motif_visite.required' => 'Le motif de visite est obligatoire pour chaque personne.',
    ]);

    // Validations personnalisées pour chaque personne
    foreach ($request->personnes as $index => $personne) {
        // Validation des heures pour chaque personne
        if (isset($personne['heure_fin_visite']) && $personne['heure_visite']) {
            if (strtotime($personne['heure_fin_visite']) <= strtotime($personne['heure_visite'])) {
                return redirect()->back()
                    ->withErrors(["personnes.{$index}.heure_fin_visite" => "L'heure de fin doit être après l'heure de début pour la personne " . ($index + 1) . "."])
                    ->withInput();
            }
        }

        // Validation que la date et heure de visite ne sont pas dans le passé pour chaque personne
        $dateTimeVisite = $personne['date_visite'] . ' ' . $personne['heure_visite'];
        if (strtotime($dateTimeVisite) < time()) {
            return redirect()->back()
                ->withErrors(["personnes.{$index}.heure_visite" => "La date et l'heure de visite ne peuvent pas être dans le passé pour la personne " . ($index + 1) . "."])
                ->withInput();
        }

        // Validation cohérence type_demande avec le nombre de personnes
        $typeDemande = $request->type_demande;
        $nbrePerso = (int)$request->nbre_perso;
        
        if ($typeDemande === 'moi' && $nbrePerso !== 1) {
            return redirect()->back()
                ->withErrors(['nbre_perso' => 'Pour "moi-même uniquement", le nombre de personnes doit être 1.'])
                ->withInput();
        }
        
        if ($typeDemande === 'moi_autres' && $nbrePerso < 2) {
            return redirect()->back()
                ->withErrors(['nbre_perso' => 'Pour "moi et d\'autres personnes", le nombre de personnes doit être au moins 2.'])
                ->withInput();
        }
        
        if ($typeDemande === 'autres' && $nbrePerso < 1) {
            return redirect()->back()
                ->withErrors(['nbre_perso' => 'Pour "d\'autres personnes uniquement", le nombre de personnes doit être au moins 1.'])
                ->withInput();
        }
    }

    // Vérification du nombre de personnes cohérent
    if (count($request->personnes) !== (int)$request->nbre_perso) {
        return redirect()->back()
            ->withErrors(['nbre_perso' => 'Le nombre de personnes doit correspondre au nombre de fiches renseignées.'])
            ->withInput();
    }

    try {
        DB::beginTransaction();

        // Vérification de l'authentification
        if (!Auth::guard('demandeur')->check()) {
            throw new \Exception('Utilisateur non authentifié.');
        }

        $demandeur = Auth::guard('demandeur')->user();
        $demandeurId = $demandeur->id;

        // Génération des identifiants uniques
        $numeroDemande = $this->generateNumeroDemande();
        $groupeId = Str::uuid()->toString();

        // Gestion des fichiers uploadés (documents joints communs)
        $documentsPaths = [];
        if ($request->hasFile('documents_joints')) {
            foreach ($request->file('documents_joints') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . Str::slug($originalName) . '.' . $extension;
                $path = $file->storeAs('documents/demandes', $fileName, 'public');
                $documentsPaths[] = $path;
            }
        }

        // Informations communes pour le groupe
        $informationsCommunes = [
            'nbre_perso' => $validated['nbre_perso'],
            'numero_ticket' => $validated['numero_ticket'],
            'numero_demande' => $numeroDemande,
            'demandeur_id' => $demandeurId,
            'documents_joints' => !empty($documentsPaths) ? json_encode($documentsPaths) : null,
            'statut' => 'en_attente',
            'groupe_id' => $groupeId,
            'user_agent' => $request->header('User-Agent'),
            'type_demande' => $validated['type_demande'],
        ];

        // Créer une entrée pour chaque personne avec ses informations individuelles
        foreach ($validated['personnes'] as $index => $personne) {
            $profilePicturePath = null;
            
            // Gestion de la photo de profil pour chaque personne
            if (isset($personne['profile_picture']) && $personne['profile_picture'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $personne['profile_picture'];
                $fileName = time() . '_' . $index . '_' . Str::slug($file->getClientOriginalName());
                $profilePicturePath = $file->storeAs('profile_pictures/personnes', $fileName, 'public');
            }

            // Déterminer si c'est le demandeur principal (première personne pour 'moi' et 'moi_autres')
            $estDemandeurPrincipal = false;
            $typeDemande = $request->type_demande;
            
            if ($typeDemande === 'moi') {
                $estDemandeurPrincipal = true; // Toutes les personnes sont le demandeur principal dans ce cas (normalement 1 seule)
            } elseif ($typeDemande === 'moi_autres') {
                $estDemandeurPrincipal = ($index === 0); // Seulement la première personne
            } elseif ($typeDemande === 'autres') {
                $estDemandeurPrincipal = false; // Aucune personne n'est le demandeur principal
            }

            // Préparer les informations véhicule si elles existent
            $infosVehicule = [];
            if (!empty($personne['marque_voiture']) || !empty($personne['modele_voiture']) || !empty($personne['immatriculation_voiture'])) {
                $infosVehicule = [
                    'marque_voiture' => $personne['marque_voiture'] ?? null,
                    'modele_voiture' => $personne['modele_voiture'] ?? null,
                    'immatriculation_voiture' => $personne['immatriculation_voiture'] ?? null,
                    'type_intervention' => $personne['type_intervention'] ?? null,
                ];
            }

            // Création de la personne avec toutes ses informations individuelles
            PersonneDemande::create(array_merge($informationsCommunes, [
                // Informations personnelles
                'name' => $personne['name'],
                'prenom' => $personne['prenom'],
                'contact' => $personne['contact'],
                'email' => $personne['email'],
                'adresse' => $personne['adresse'],
                'fonction' => $personne['fonction'],
                'structure' => $personne['structure'],
                'profile_picture' => $profilePicturePath,
                
                // Informations de visite individuelles
                'date_visite' => $personne['date_visite'],
                'date_fin_visite' => $personne['date_fin_visite'],
                'heure_visite' => $personne['heure_visite'],
                'heure_fin_visite' => $personne['heure_fin_visite'] ?? null,
                'motif_visite' => $personne['motif_visite'],
                'description_detaille' => $personne['description_detaille'] ?? null,
                
                // Informations véhicule individuelles
                'marque_voiture' => $infosVehicule['marque_voiture'] ?? null,
                'modele_voiture' => $infosVehicule['modele_voiture'] ?? null,
                'immatriculation_voiture' => $infosVehicule['immatriculation_voiture'] ?? null,
                'type_intervention' => $infosVehicule['type_intervention'] ?? null,
                
                // Statut
                'est_demandeur_principal' => $estDemandeurPrincipal,
            ]));
        }

        DB::commit();

        // Récupérer la première personne pour la redirection
        $premierePersonne = PersonneDemande::where('groupe_id', $groupeId)
            ->orderBy('created_at', 'asc')
            ->first();

        // Redirection avec message de succès
        return redirect()->route('demandes.show', $premierePersonne->id)
            ->with('success', 'Votre demande a été soumise avec succès. Numéro de demande: ' . $numeroDemande);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Erreur lors de la création de la demande: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Une erreur est survenue lors de la soumission de votre demande: ' . $e->getMessage())
            ->withInput();
    }
}

     /**
     * Génère un numéro de demande unique
     */
    private function generateNumeroDemande()
    {
        $prefix = 'DEM';
        $date = now()->format('Ymd');
        
        do {
            $random = Str::upper(Str::random(6));
            $numeroDemande = $prefix . '-' . $date . '-' . $random;
        } while (Demande::where('numero_demande', $numeroDemande)->exists());
        
        return $numeroDemande;
    }

    /**
     * Afficher les détails d'une demande
     */
    public function show($id)
    {
        // Récupérer la personne demandée
        $personne = PersonneDemande::findOrFail($id);
        
        // Vérifier que l'utilisateur connecté a le droit de voir cette demande
        if (Auth::guard('demandeur')->check()) {
            $user = Auth::guard('demandeur')->user();
            if ($personne->demandeur_id !== $user->id) {
                abort(403, 'Accès non autorisé à cette demande.');
            }
        }
        
        // Récupérer toutes les personnes du même groupe
        $personnesGroupe = PersonneDemande::where('groupe_id', $personne->groupe_id)
            ->orderBy('est_demandeur_principal', 'desc')
            ->orderBy('created_at')
            ->get();
        
        return view('demandeur.demandes.show', compact('personne', 'personnesGroupe'));
    }
}
