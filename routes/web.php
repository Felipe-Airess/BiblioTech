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

Route::get('/', function () {
    return view('welcome');
});
//Middleware para proteger as rotas de cadastro de bibliotecário e livro, só o gerente pode acessar

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

// Rotas para o CRUD de Livros

//Rotas para o CRUD de Membros
Route::get('/membros/novo', [MembrosController::class, 'create'])->name('membros.create');
Route::post('/membros/salvar', [MembrosController::class, 'store'])->name('membros.store');

Route::post('/livros/{id}/alugar', [EmprestimoController::class, 'alugar'])->name('livros.alugar'); // Só membros podem alugar livros
Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show'); // Rotas públicas para listar e ver detalhes dos livros
Route::get('/dashboard', function () {
    // Busca todos os livros cadastrados, do mais novo pro mais velho
    $livros = Livros::latest()->get(); 
    
    // Manda a variável $livros lá pra dentro do HTML do dashboard
    return view('dashboard', compact('livros')); 
})->middleware(['auth:web,membro'])->name('dashboard');


    Route::middleware(['auth:web,membro'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });


require __DIR__.'/auth.php';
