<?php

use App\Http\Controllers\Admin\AdminDashboard;
use App\Http\Controllers\Admin\AuthenticateAdmin;
use App\Http\Controllers\Admin\Code\CodeController;
use App\Http\Controllers\Admin\Demande\AdminDemandeController;
use App\Http\Controllers\Admin\Demande\AdminVisiteController;
use App\Http\Controllers\Admin\InfoController;
use App\Http\Controllers\Agent\AgentController;
use App\Http\Controllers\Agent\AgentDashboard;
use App\Http\Controllers\Agent\AgentPointageController;
use App\Http\Controllers\Agent\AuthenticateAgent;
use App\Http\Controllers\Agent\Code\AgentCodeController;
use App\Http\Controllers\Agent\Visite\SortieController;
use App\Http\Controllers\Agent\Visite\VisiteController;
use App\Http\Controllers\Demande\DemandeController;
use App\Http\Controllers\Demandeur\DemandeurAuthenticate;
use App\Http\Controllers\Demandeur\DemandeurControlleur;
use App\Http\Controllers\Demandeur\DemandeurDashboard;
use App\Http\Controllers\Demandeur\DemandeurPages;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Personal\AllVisiteController;
use App\Http\Controllers\Personal\CarteAccesController;
use App\Http\Controllers\Personal\PersonalAuthenticate;
use App\Http\Controllers\Personal\PersonalController;
use App\Http\Controllers\Personal\PersonalDashboard;
use App\Http\Controllers\Societe\SocieteAuthenticate;
use App\Http\Controllers\Societe\SocieteController;
use App\Http\Controllers\Societe\SocieteDashboard;
use App\Http\Controllers\Societe\SocietePages;
use Illuminate\Support\Facades\Route;


//Les routes des pages standards 
Route::prefix('/')->group(function(){
    Route::get('/',[HomeController::class,'home'])->name('pages.home');
    Route::get('/contact',[HomeController::class,'contact'])->name('pages.contact');
    Route::get('/about',[HomeController::class,'about'])->name('pages.about');
    Route::get('/service',[HomeController::class,'service'])->name('pages.service');
    Route::get('/request/access',[HomeController::class,'access'])->name('pages.access');
});

//Les routes de gestion du @admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthenticateAdmin::class, 'login'])->name('admin.login');
    Route::post('/login', [AuthenticateAdmin::class, 'handleLogin'])->name('admin.handleLogin');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminDashboard::class, 'logout'])->name('admin.logout');

    //les routes de gestion des demandes par l'admin 
    Route::prefix('request')->group(function(){
        Route::get('/indexed',[AdminDemandeController::class,'index'])->name('admin.demandes.index');
        Route::get('/{id}', [AdminDemandeController::class, 'show'])->name('admin.request.show'); // Changé ici
        Route::post('/{id}/approve', [AdminDemandeController::class, 'approve'])->name('admin.request.approve'); // Changé ici
        Route::post('/{id}/reject', [AdminDemandeController::class, 'reject'])->name('admin.request.reject'); // Changé ici
        Route::post('/{id}/cancel', [AdminDemandeController::class, 'cancel'])->name('admin.request.cancel'); // Changé ici
        Route::get('/export', [AdminDemandeController::class, 'export'])->name('admin.request.export'); // Changé ici
    });

    Route::prefix('claimant')->group(function(){
        Route::get('/create',[DemandeurControlleur::class,'create'])->name('demandeur.create');
        Route::post('/store', [DemandeurControlleur::class, 'store'])->name('demandeur.store');
        Route::get('/indexAll', [DemandeurControlleur::class, 'index'])->name('demandeur.index');
        Route::get('/{id}', [DemandeurControlleur::class, 'show'])->name('demandeur.show');
        Route::get('/{demandeur}/edit', [DemandeurControlleur::class, 'edit'])->name('demandeur.edit');
        Route::put('/{demandeur}', [DemandeurControlleur::class, 'update'])->name('demandeur.update');
        Route::patch('/{demandeur}/toggle-archive', [DemandeurControlleur::class, 'toggleArchive'])->name('demandeur.toggle-archive');
    });

    //ajout des sociétés par l'admin
    Route::prefix('society')->group(function(){
        Route::get('/create',[SocieteController::class,'create'])->name('society.create');
        Route::post('/store', [SocieteController::class, 'store'])->name('society.store');
        Route::get('/index', [SocieteController::class, 'index'])->name('society.index');
        Route::patch('/{societe}/toggle-archive', [SocieteController::class, 'toggleArchive'])->name('society.toggle-archive');
    }); 

    //ajouts des agents permanents
    Route::prefix('agent')->group(function(){
        Route::get('/list',[AgentController::class,'index'])->name('agent.index');
        Route::get('/introduce',[AgentController::class,'create'])->name('agent.create');
        Route::post('/introduce',[AgentController::class,'store'])->name('agent.store');
        Route::get('/agent/{agent}/edit', [AgentController::class, 'edit'])->name('agent.edit');
        Route::put('/agent/{agent}', [AgentController::class, 'update'])->name('agent.update');
        Route::post('agent/{id}/archive', [AgentController::class, 'archive'])->name('agent.archive');
        Route::post('agent/{id}/restore', [AgentController::class, 'restore'])->name('agent.restore');
        Route::delete('agent/{id}/delete', [AgentController::class, 'destroy'])->name('agent.destroy');
    });

    Route::get('/presence', [InfoController::class, 'info'])->name('agent.info');
    Route::get('/stats', [InfoController::class, 'getAgentStats'])->name('admin.agent.stats');
    Route::get('/scans', [InfoController::class, 'getScansData'])->name('admin.agent.scans');
    Route::get('/pointages', [InfoController::class, 'getPointagesData'])->name('admin.agent.pointages');
    Route::get('/dashboard-stats', [InfoController::class, 'getDashboardStats'])->name('admin.agent.dashboard-stats');

    Route::get('history/visite',[AdminVisiteController::class,'history'])->name('admin.visite.history');

    // Routes pour les personnels permanents
    Route::prefix('admin')->group(function() {
        Route::get('/permanent-personnel/all', [PersonalController::class, 'index'])->name('admin.permanent-personnel.index');
        Route::get('/permanent-personnel/save', [PersonalController::class, 'create'])->name('admin.permanent-personnel.create');
        Route::post('/permanent-personnel', [PersonalController::class, 'store'])->name('admin.permanent-personnel.store');
        Route::get('/permanent-personnel/{id}', [PersonalController::class, 'show'])->name('admin.permanent-personnel.show');
        Route::get('/permanent-personnel/{id}/card', [PersonalController::class, 'generateCard'])->name('admin.permanent-personnel.card');
        Route::get('/permanent-personnel/{id}/download-card', [PersonalController::class, 'downloadCard'])->name('admin.permanent-personnel.download-card');
        Route::post('/permanent-personnel/{id}/renew', [PersonalController::class, 'renew'])->name('admin.permanent-personnel.renew');
        Route::post('/permanent-personnel/{id}/desactivate', [PersonalController::class, 'desactivate'])->name('admin.permanent-personnel.desactivate');
        Route::post('/permanent-personnel/{id}/activate', [PersonalController::class, 'activate'])->name('admin.permanent-personnel.activate');
        
        // Vérification de code d'accès
        Route::post('/permanent-personnel/check-code', [PersonalController::class, 'checkCodeAccess'])->name('admin.permanent-personnel.check-code');
    });

    //Les routes pour les codes QR d'accès 
    Route::prefix('code')->group(function(){
        Route::get('/code-acces/eng', [CodeController::class, 'create'])->name('admin.code.create');
        Route::post('/code-acces', [CodeController::class, 'store'])->name('admin.code.store');
        Route::get('/code-acces/{id}/code-qr', [CodeController::class, 'show'])->name('admin.code.show');
        Route::get('/code-acces//code-qr', [CodeController::class, 'index'])->name('admin.code.index');
        Route::post('/code-acces/scanner', [CodeController::class, 'scanner'])->name('admin.code.scanner');
        Route::get('/code-acces/{id}/download', [CodeController::class, 'download'])->name('admin.code.download');
        Route::delete('/code-acces/{code_acces}', [CodeController::class, 'destroy'])->name('admin.code.destroy');
    });
});

//Les routes de gestion du @societe
Route::prefix('claimant')->group(function () {
    Route::get('/login', [DemandeurAuthenticate::class, 'login'])->name('demandeur.login');
    Route::post('/login', [DemandeurAuthenticate::class, 'handleLogin'])->name('demandeur.handleLogin');
});
Route::middleware('demandeur')->prefix('claimant')->group(function () {
    Route::get('/dashboard', [DemandeurDashboard::class, 'dashboard'])->name('demandeur.dashboard');
    Route::post('/logout', [DemandeurDashboard::class, 'logout'])->name('demandeur.logout');

    //les routes de pages d'un demandeur connecté
    Route::get('/contact',[DemandeurPages::class,'contact'])->name('demandeur.pages.contact');
    Route::get('/about',[DemandeurPages::class,'about'])->name('demandeur.pages.about');
    Route::get('/service',[DemandeurPages::class,'service'])->name('demandeur.pages.service');

     //Les routes pour la demande d'accès
    Route::prefix('request')->group(function(){
        Route::get('/list',[DemandeController::class,'list'])->name('demandes.list');
        Route::get('/create',[DemandeController::class,'create'])->name('demandes.create');
        Route::post('/create',[DemandeController::class,'store'])->name('demandes.store');
        Route::get('/{id}', [DemandeController::class, 'show'])->name('demandes.show');
    });
});

//Les routes de gestion des @personalpermanent
Route::prefix('personal')->group(function(){
    Route::get('/login', [PersonalAuthenticate::class, 'login'])->name('personal.login');
    Route::post('/login', [PersonalAuthenticate::class, 'handleLogin'])->name('personal.handleLogin');
});

Route::middleware('personal')->prefix('personal')->group(function(){
    Route::get('/dashboard', [PersonalDashboard::class, 'dashboard'])->name('personal.dashboard');
    Route::get('/logout', [PersonalDashboard::class, 'logout'])->name('personal.logout');

    //La route de la carte d'accès du personnel permanent 
    Route::get('/my-card', [CarteAccesController::class, 'showMyCard'])->name('show-card');
    Route::get('/download-my-card', [CarteAccesController::class, 'downloadMyCard'])->name('download-card');
});

//Les routes de gestion des @agents
Route::prefix('agent')->group(function() {
    Route::get('/login', [AuthenticateAgent::class, 'login'])->name('agent.login');
    Route::post('/login', [AuthenticateAgent::class, 'handleLogin'])->name('agent.handleLogin');
});

Route::middleware('agent')->prefix('agent')->group(function(){
    Route::get('/dashboard', [AgentDashboard::class, 'dashboard'])->name('agent.dashboard');
    Route::get('/visite/recent', [AgentDashboard::class, 'recentVisites'])->name('visite.recent');
    Route::get('/dashboard/stats', [AgentController::class, 'dashboardStats'])->name('agent.dashboard.stats');
    Route::get('/logout', [AgentDashboard::class, 'logout'])->name('agent.logout');

    //Les routes pour l'enregistrement des visites d'entrée
    Route::get('/visite/save',[VisiteController::class,'create'])->name('visite.create');
    Route::get('/visite/accès',[VisiteController::class,'access'])->name('visite.access');
    Route::post('/visite/check-code', [VisiteController::class, 'checkCodeAccess'])->name('visite.check-code');
    Route::post('/visite', [VisiteController::class, 'store'])->name('visite.store');
    Route::get('/visite/success', [VisiteController::class, 'success'])->name('visite.success');
    Route::post('/visite/get-permanents', [VisiteController::class, 'getPersonnesPermanentes'])->name('visite.get-permanents');

    //Les routes pour l'enregistrement des visites de sortie 
    Route::get('/visite/sortie', [SortieController::class, 'sortie'])->name('visite.sortie');
    Route::post('/visite/check-sortie-code', [SortieController::class, 'checkSortieCode'])->name('visite.check-sortie-code');
    Route::post('/visite/store-sortie', [SortieController::class, 'storeSortie'])->name('visite.store-sortie');
    Route::get('/visite/sortie-success', [SortieController::class, 'sortieSuccess'])->name('visite.sortie-success');
    Route::get('history/visited',[SortieController::class,'history'])->name('visite.history');

    //Les routes d'enregistrement des pointages de l'agent
    Route::post('/pointer-arrivee', [AgentPointageController::class, 'pointerArrivee'])->name('agent.pointer-arrivee');
    Route::post('/pointer-depart', [AgentPointageController::class, 'pointerDepart'])->name('agent.pointer-depart');
    Route::get('/statut-pointage', [AgentPointageController::class, 'getStatutPointage'])->name('agent.statut-pointage');

    //Les routes pour l'agent de scanner pour les controlles 
    Route::get('/scanner', [AgentCodeController::class, 'showScanner'])->name('agent.scanner.view');
    Route::post('/scan', [AgentCodeController::class, 'scannerAgent'])->name('agent.scan');
    Route::get('/scan/historique', [AgentCodeController::class, 'historiqueScansAgent'])->name('agent.scan.historique');
    Route::get('/scan/statut', [AgentCodeController::class, 'statutScanAgent'])->name('agent.scan.statut');
    
});

//Les routes de gestion du @societe
Route::prefix('society')->group(function () {
    Route::get('/login', [SocieteAuthenticate::class, 'login'])->name('society.login');
    Route::post('/login', [SocieteAuthenticate::class, 'handleLogin'])->name('society.handleLogin');
});
Route::middleware('societe')->prefix('society')->group(function () {
    Route::get('/dashboard', [SocieteDashboard::class, 'dashboard'])->name('society.dashboard');
    Route::post('/logout', [SocieteDashboard::class, 'logout'])->name('society.logout');

    //Les routes pour la demande d'accès
    // Route::prefix('request')->group(function(){
    //     Route::get('/create',[DemandeController::class,'create'])->name('demandes.create');
    //     Route::post('/create',[DemandeController::class,'store'])->name('demandes.store');
    //     Route::get('/{id}', [DemandeController::class, 'show'])->name('demandes.show');
    // });

    //les routes de pages d'une societé connecté
    Route::get('/contact',[SocietePages::class,'contact'])->name('society.pages.contact');
    Route::get('/about',[SocietePages::class,'about'])->name('society.pages.about');
    Route::get('/service',[SocietePages::class,'service'])->name('society.pages.service');
});


//Les routes definition du accès 
Route::get('/validate-society-account/{email}', [SocieteAuthenticate::class, 'defineAccess']);
Route::post('/validate-society-account/{email}', [SocieteAuthenticate::class, 'submitDefineAccess'])->name('society.validate');
Route::get('/validate-claimant-account/{email}', [DemandeurAuthenticate::class, 'defineAccess']);
Route::post('/validate-claimant-account/{email}', [DemandeurAuthenticate::class, 'submitDefineAccess'])->name('demandeur.validate');
Route::get('/validate-agent-account/{email}', [AuthenticateAgent::class, 'defineAccess']);
Route::post('/validate-agent-account/{email}', [AuthenticateAgent::class, 'submitDefineAccess'])->name('agent.validate');
Route::get('/validate-personal-account/{email}', [PersonalAuthenticate::class, 'defineAccess']);
Route::post('/validate-personal-account/{email}', [PersonalAuthenticate::class, 'submitDefineAccess'])->name('personal.validate');
