<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\agentRequest;
use App\Models\Agent;
use App\Models\ResetCodePasswordAgent;
use App\Notifications\SendEmailToAgentAfterRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::latest()->paginate(10);
        $communes = Agent::distinct()->pluck('commune');
        $activeAgentsCount = Agent::whereNull('archived_at')->count();
        $archivedAgentsCount = Agent::whereNotNull('archived_at')->count();
        $urgentCasesCount = Agent::whereNotNull('cas_urgence')->count();
        
        return view('admin.agent.index', compact(
            'agents', 
            'communes', 
            'activeAgentsCount', 
            'archivedAgentsCount', 
            'urgentCasesCount'
        ));
    }

    public function create(){
        return view('admin.agent.create');
    }

    public function store(agentRequest $request)
    {
        try {

            $existingAgent = Agent::where('email', $request->email)->first();
            if ($existingAgent) {
                return redirect()->back()->withErrors(['email' => 'Cet email est déjà utilisé.'])->withInput();
            }

            $agent = new Agent();
            $agent->name = $request->name;
            $agent->prenom = $request->prenom;
            $agent->email = $request->email;
            $agent->contact = $request->contact;
            $agent->cas_urgence = $request->cas_urgence;
            $agent->password = Hash::make('default');
            
            if ($request->hasFile('profile_picture')) {
                $request->validate([
                    'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);
                
                $agent->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            $agent->commune = $request->commune;
            
            $agent->save();

            // Envoi de l'e-mail de vérification
            ResetCodePasswordAgent::where('email', $agent->email)->delete();
            $code1 = rand(1000, 4000);
            $code = $code1 . '' . $agent->id;

            ResetCodePasswordAgent::create([
                'code' => $code,
                'email' => $agent->email,
            ]);

            Notification::route('mail', $agent->email)
                ->notify(new SendEmailToAgentAfterRegistrationNotification($code, $agent->email));
            DB::commit();

            return redirect()->route('agent.index')->with('success', 'L\'agent a bien été enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'enregistrement de l\'agent: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.'])->withInput();
        }
    }

    public function show(Agent $agent)
    {
        return view('etatCivil.agent.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        return view('admin.agent.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email,' . $agent->id,
            'contact' => 'required|string|max:20',
            'commune' => 'required|string|max:255',
            'cas_urgence' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Gestion de la photo de profil
            if ($request->hasFile('profile_picture')) {
                // Supprimer l'ancienne photo si elle existe
                if ($agent->profile_picture && Storage::exists($agent->profile_picture)) {
                    Storage::delete($agent->profile_picture);
                }
                
                // Stocker la nouvelle photo
                $validated['profile_picture'] = $request->file('profile_picture')->store('agents/profile_pictures', 'public');
            } else {
                // Garder l'ancienne photo
                $validated['profile_picture'] = $agent->profile_picture;
            }

            // Mise à jour de l'agent
            $agent->update($validated);

            return redirect()->route('agent.index')
                ->with('success', 'Agent modifié avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    public function archive($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->archived_at = now();
        $agent->save();

        return redirect()->route('agent.index')->with('success', 'Agent archivé avec succès.');
    }

    // Méthode pour restaurer un agent
    public function restore($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->archived_at = null;
        $agent->save();

        return redirect()->route('agent.index')->with('success', 'Agent restauré avec succès.');
    }

    // Méthode pour supprimer un agent
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return redirect()->route('agent.index')->with('success', 'Agent supprimé avec succès.');
    }

}
