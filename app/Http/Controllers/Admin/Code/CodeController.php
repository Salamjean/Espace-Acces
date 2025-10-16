<?php

namespace App\Http\Controllers\Admin\Code;

use App\Http\Controllers\Controller;
use App\Models\CodeAcces;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CodeController extends Controller
{
    public function create()
    {
        return view('admin.code.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_porte' => 'required|string|max:255',
            'type' => 'required|in:entree,sortie',
            'duree_validite' => 'nullable|integer|min:1'
        ]);

        try {
            // Générer un code unique
            $codeUnique = CodeAcces::genererCodeUnique();

            // Créer les données pour le QR code
            $qrData = json_encode([
                'code' => $codeUnique,
                'porte' => $request->nom_porte,
                'type' => $request->type,
                'timestamp' => now()->timestamp
            ]);

            // Générer le QR code
            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->data($qrData)
                ->size(400) // Taille augmentée pour meilleure qualité
                ->margin(20)
                ->build();

            // Créer le nom du fichier
            $fileName = 'qr_codes/' . $codeUnique . '_' . now()->format('Ymd_His') . '.png';

            // Enregistrer dans le storage
            Storage::disk('public')->put($fileName, $qrCode->getString());

            // Créer le code d'accès
            $codeAcces = CodeAcces::create([
                'nom_porte' => $request->nom_porte,
                'type' => $request->type,
                'code_unique' => $codeUnique,
                'qr_code_path' => $fileName,
                'expire_at' => $request->duree_validite ? now()->addHours($request->duree_validite) : null
            ]);

            return redirect()->route('admin.code.show', $codeAcces->id)
                ->with('success', 'Code QR généré et enregistré avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du QR code: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $codeAcces = CodeAcces::findOrFail($id);
        return view('admin.code.show', compact('codeAcces'));
    }

    public function index()
    {
        $codes = CodeAcces::latest()->paginate(10);
        return view('admin.code.index', compact('codes'));
    }

    // Télécharger le QR code
    public function download($id)
    {
        $codeAcces = CodeAcces::findOrFail($id);

        if (!$codeAcces->qr_code_path || !Storage::disk('public')->exists($codeAcces->qr_code_path)) {
            return redirect()->back()->with('error', 'Fichier QR code non trouvé.');
        }

        return Storage::disk('public')->download($codeAcces->qr_code_path, 
            'qr_code_' . $codeAcces->code_unique . '.png');
    }

    // Méthode pour scanner le QR code
    public function scanner(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || !isset($qrData['code'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code invalide'
                ], 400);
            }

            $codeAcces = CodeAcces::where('code_unique', $qrData['code'])->first();

            if (!$codeAcces) {
                return response()->json([
                    'success' => false,
                    'message' => 'Code non trouvé'
                ], 404);
            }

            if (!$codeAcces->estValide()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Code expiré ou désactivé'
                ], 400);
            }

            // Ici, vous pouvez enregistrer la présence
            // Par exemple, créer un enregistrement dans une table "presences"

            return response()->json([
                'success' => true,
                'message' => 'Présence enregistrée avec succès',
                'data' => [
                    'porte' => $codeAcces->nom_porte,
                    'type' => $codeAcces->type,
                    'heure' => now()->format('H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du scan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(CodeAcces $code_acces)
    {
        try {
            // Supprimer le fichier QR code si il existe
            if ($code_acces->qr_code_path && Storage::exists($code_acces->qr_code_path)) {
                Storage::delete($code_acces->qr_code_path);
            }

            // Supprimer le code d'accès
            $code_acces->delete();

            return response()->json([
                'success' => true,
                'message' => 'Code d\'accès supprimé avec succès!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
}
