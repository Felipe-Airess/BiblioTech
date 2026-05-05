<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\LivroController;
use App\Models\Livros;  
use App\Models\Membros;
use App\Http\Controllers\MembrosController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\EmprestimoAdminController;
use App\Http\Controllers\AutorController;
use Illuminate\Support\Facades\Schedule;

Route::get('/', function () {
    return view('welcome');
});
//Middleware para proteger as rotas de cadastro de bibliotecário e livro, só o gerente pode acessar

Schedule::command('emprestimos:lembrar')->dailyAt('08:00');
// Rotas para o CRUD de Livros
// O 'auth' sozinho já chama o guard 'web' por padrão

    // rotas admin...
    Route::get('/admin/livros/novo', [LivroController::class, 'create'])->name('livros.create')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/livros/salvar', [LivroController::class, 'store'])->name('livros.store')->middleware('tipo:gerente,bibliotecario');
    Route::delete('/admin/livros/{id}', [LivroController::class, 'destroy'])->name('livros.destroy')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/livros/{id}/editar', [LivroController::class, 'edit'])->name('livros.edit')->middleware('tipo:gerente,bibliotecario');
    Route::put('/admin/livros/{id}', [LivroController::class, 'update'])->name('livros.update')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/emprestimos', [EmprestimoAdminController::class, 'index'])->name('admin.emprestimos.index')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/emprestimos/{id}/devolver', [EmprestimoAdminController::class, 'devolver'])->name('admin.emprestimos.devolver')->middleware('tipo:gerente,bibliotecario');
    // Rotas para o CRUD de Bibliotecários
    Route::post('/admin/bibliotecarios/salvar', [FuncionarioController::class, 'store'])->name('bibliotecarios.store')->middleware('tipo:gerente');
    Route::get('/admin/bibliotecarios/novo', [FuncionarioController::class, 'create'])->name('bibliotecarios.create')->middleware('tipo:gerente');

Route::get('/emprestimos/{id}/comprovante', [EmprestimoController::class, 'comprovante'])
    ->middleware('auth:membro')
    ->name('emprestimos.comprovante');

//Rotas para o CRUD de Membros
Route::get('/membros/novo', [MembrosController::class, 'create'])->name('membros.create');
Route::post('/membros/salvar', [MembrosController::class, 'store'])->name('membros.store');

Route::post('/livros/{id}/alugar', [EmprestimoController::class, 'alugar'])->name('livros.alugar'); // Só membros podem alugar livros
Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show'); // Rotas públicas para listar e ver detalhes dos livros

Route::get('/dashboard', [LivroController::class, 'dashboard'])
    ->middleware(['auth:web,membro'])
    ->name('dashboard');


    Route::middleware(['auth:web,membro'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/admin/autores', [AutorController::class, 'index'])->name('autores.index')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/autores/novo', [AutorController::class, 'create'])->name('autores.create')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/autores/salvar', [AutorController::class, 'store'])->name('autores.store')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/autores/{id}/editar', [AutorController::class, 'edit'])->name('autores.edit')->middleware('tipo:gerente,bibliotecario');
    Route::put('/admin/autores/{id}', [AutorController::class, 'update'])->name('autores.update')->middleware('tipo:gerente,bibliotecario');
    Route::delete('/admin/autores/{id}', [AutorController::class, 'destroy'])->name('autores.destroy')->middleware('tipo:gerente,bibliotecario');
    Route::get('/autores/{id}', [AutorController::class, 'show'])->name('autores.show'); // Pública para ver detalhes

    Route::get('/meus-emprestimos', [EmprestimoController::class, 'historico'])
    ->middleware('auth:membro')
    ->name('emprestimos.historico');
require __DIR__.'/auth.php';
