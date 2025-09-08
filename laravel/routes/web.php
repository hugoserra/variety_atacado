<?php

use App\Http\Middleware\IsAdmin;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () 
{
    // Rotas protegidas por IsAdmin
    Route::middleware(IsAdmin::class)->group(function () {
        Route::view('/', 'screens.dashboard')->name('home');
        Route::view('/dashboard', 'screens.dashboard')->name('dashboard');
        Route::view('pedidos', 'screens.pedidos')->name('pedidos');
        Route::view('produtos', 'screens.produtos')->name('produtos');
        Route::view('clientes', 'screens.clientes')->name('clientes');
        Route::view('fornecedores', 'screens.fornecedores')->name('fornecedores');
    });

    // Rotas sem IsAdmin
    Route::view('transacoes', 'screens.transacoes')->name('transacoes');
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
