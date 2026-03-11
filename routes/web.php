<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\LivroController;
use App\Models\Livros;


Route::get('/', function () {
    return view('welcome');
});
// Rotas para o CRUD de Livros
Route::get('/admin/livros/novo', [LivroController::class, 'create'])->name('livros.create');
Route::post('/admin/livros/salvar', [LivroController::class, 'store'])->name('livros.store');
// Rotas para o CRUD de Bibliotecários
Route::post('/admin/bibliotecarios/salvar', [FuncionarioController::class, 'store'])->name('bibliotecarios.store');
Route::get('/admin/bibliotecarios/novo', [FuncionarioController::class, 'create'])->name('bibliotecarios.create');

Route::get('/dashboard', function () {
    // Busca todos os livros cadastrados, do mais novo pro mais velho
    $livros = Livros::latest()->get(); 
    
    // Manda a variável $livros lá pra dentro do HTML do dashboard
    return view('dashboard', compact('livros')); 
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
