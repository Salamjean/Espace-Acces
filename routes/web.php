<?php

use App\Http\Controllers\Admin\AdminDashboard;
use App\Http\Controllers\Admin\AuthenticateAdmin;
use App\Http\Controllers\Demande\DemandeController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Societe\SocieteController;
use Illuminate\Support\Facades\Route;


//Les routes des pages standards 
Route::prefix('/')->group(function(){
    Route::get('/',[HomeController::class,'home'])->name('pages.home');
    Route::get('/contact',[HomeController::class,'contact'])->name('pages.contact');
    Route::get('/about',[HomeController::class,'about'])->name('pages.about');
    Route::get('/service',[HomeController::class,'service'])->name('pages.service');
});

Route::prefix('request')->group(function(){
    Route::get('/create',[DemandeController::class,'create'])->name('demandes.create');
    Route::post('/create',[DemandeController::class,'store'])->name('demandes.store');
});

//Les routes de gestion du @admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthenticateAdmin::class, 'login'])->name('admin.login');
    Route::post('/login', [AuthenticateAdmin::class, 'handleLogin'])->name('admin.handleLogin');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminDashboard::class, 'logout'])->name('admin.logout');

    //ajout des sociétés par l'admin
    Route::prefix('society')->group(function(){
        Route::get('/create',[SocieteController::class,'create'])->name('society.create');
        Route::post('/store', [SocieteController::class, 'store'])->name('society.store');
        Route::get('/index', [SocieteController::class, 'index'])->name('society.index');
        Route::patch('/{societe}/toggle-archive', [SocieteController::class, 'toggleArchive'])->name('society.toggle-archive');
    }); 
});
