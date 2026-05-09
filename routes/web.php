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
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\MinhaBibliotecaController;
use App\Http\Controllers\CarteirinhaController;
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
    Route::post('/admin/emprestimos/{id}/aprovar', [EmprestimoAdminController::class, 'aprovar'])->name('admin.emprestimos.aprovar')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/emprestimos/{id}/retirar', [EmprestimoAdminController::class, 'retirar'])->name('admin.emprestimos.retirar')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/emprestimos/{id}/iniciar-uso', [EmprestimoAdminController::class, 'iniciarUso'])->name('admin.emprestimos.iniciar-uso')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/emprestimos/{id}/encerrar', [EmprestimoAdminController::class, 'encerrar'])->name('admin.emprestimos.encerrar')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/emprestimos/{id}/regularizar-multa', [EmprestimoAdminController::class, 'regularizarMulta'])->name('admin.emprestimos.regularizar-multa')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/reservas/{id}/atender', [EmprestimoAdminController::class, 'atenderReserva'])->name('admin.reservas.atender')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/emprestimos/{id}/rejeitar', [EmprestimoAdminController::class, 'rejeitar'])->name('admin.emprestimos.rejeitar')->middleware('tipo:gerente,bibliotecario');
    // Rotas para o CRUD de Bibliotecários
    Route::post('/admin/bibliotecarios/salvar', [FuncionarioController::class, 'store'])->name('bibliotecarios.store')->middleware('tipo:gerente');
    Route::get('/admin/bibliotecarios/novo', [FuncionarioController::class, 'create'])->name('bibliotecarios.create')->middleware('tipo:gerente');
    Route::get('/admin/bibliotecarios', [FuncionarioController::class, 'index'])->name('bibliotecarios.index')->middleware('tipo:gerente');
    Route::get('/admin/bibliotecarios/{bibliotecario}/editar', [FuncionarioController::class, 'edit'])->name('bibliotecarios.edit')->middleware('tipo:gerente');
    Route::put('/admin/bibliotecarios/{bibliotecario}', [FuncionarioController::class, 'update'])->name('bibliotecarios.update')->middleware('tipo:gerente');

Route::get('/emprestimos/{id}/comprovante', [EmprestimoController::class, 'comprovante'])
    ->middleware('auth:membro')
    ->name('emprestimos.comprovante');

//Rotas para o CRUD de Membros
Route::get('/membros/novo', [MembrosController::class, 'create'])->name('membros.create')->middleware('tipo:gerente,bibliotecario');
Route::post('/membros/salvar', [MembrosController::class, 'store'])->name('membros.store')->middleware('tipo:gerente,bibliotecario');
Route::get('/admin/membros/{membro}/editar', [MembrosController::class, 'edit'])->name('membros.edit')->middleware('tipo:gerente,bibliotecario');
Route::put('/admin/membros/{membro}', [MembrosController::class, 'update'])->name('membros.update')->middleware('tipo:gerente,bibliotecario');

Route::post('/livros/{id}/alugar', [EmprestimoController::class, 'alugar'])->name('livros.alugar'); // Só membros podem alugar livros
Route::post('/livros/{id}/reservar', [EmprestimoController::class, 'reservar'])->middleware('auth:membro')->name('livros.reservar');
Route::post('/livros/{livro}/favorito', [FavoritoController::class, 'toggle'])->middleware('auth:membro')->name('livros.favorito.toggle');
Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show'); // Rotas públicas para listar e ver detalhes dos livros
Route::post('/livros/{id}/comentarios', [LivroController::class, 'storeComentario'])
    ->middleware('auth:web,membro')
    ->name('livros.comentarios.store');
Route::put('/livros/{livro}/comentarios/{comentario}', [LivroController::class, 'updateComentario'])
    ->middleware('auth:web,membro')
    ->name('livros.comentarios.update');
Route::delete('/livros/{livro}/comentarios/{comentario}', [LivroController::class, 'destroyComentario'])
    ->middleware('auth:web,membro')
    ->name('livros.comentarios.destroy');

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

    Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('categorias.index')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('categorias.store')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/categorias/{categoria}/editar', [CategoriaController::class, 'edit'])->name('categorias.edit')->middleware('tipo:gerente,bibliotecario');
    Route::put('/admin/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update')->middleware('tipo:gerente,bibliotecario');

    Route::get('/meus-emprestimos', [EmprestimoController::class, 'historico'])
    ->middleware('auth:membro')
    ->name('emprestimos.historico');
    Route::post('/meus-emprestimos/{id}/solicitar-devolucao', [EmprestimoController::class, 'solicitarDevolucao'])
        ->middleware('auth:membro')
        ->name('emprestimos.solicitar-devolucao');
    Route::post('/meus-emprestimos/{id}/renovar', [EmprestimoController::class, 'renovar'])
        ->middleware('auth:membro')
        ->name('emprestimos.renovar');
    Route::post('/minhas-reservas/{id}/cancelar', [EmprestimoController::class, 'cancelarReserva'])
        ->middleware('auth:membro')
        ->name('reservas.cancelar');
    Route::get('/meus-favoritos', [FavoritoController::class, 'index'])
        ->middleware('auth:membro')
        ->name('favoritos.index');
    Route::get('/minha-biblioteca', [MinhaBibliotecaController::class, 'index'])
        ->middleware('auth:membro')
        ->name('membros.biblioteca');
    Route::get('/minha-carteirinha', [CarteirinhaController::class, 'show'])
        ->middleware('auth:membro')
        ->name('membros.carteirinha');
    Route::get('/minha-carteirinha/pdf', [CarteirinhaController::class, 'pdf'])
        ->middleware('auth:membro')
        ->name('membros.carteirinha.pdf');

    // Rotas admin: Perfil de Membros
    Route::get('/admin/membros/perfis', [App\Http\Controllers\PerfilMembrosController::class, 'index'])->name('admin.membros.perfis')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/membros/{membro}', [App\Http\Controllers\PerfilMembrosController::class, 'show'])->name('admin.membros.show')->middleware('tipo:gerente,bibliotecario');
    Route::post('/admin/membros/{membro}/message', [App\Http\Controllers\PerfilMembrosController::class, 'sendMessage'])->name('admin.membros.message')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/relatorios', [RelatorioController::class, 'index'])->name('admin.relatorios.index')->middleware('tipo:gerente,bibliotecario');
    Route::get('/admin/relatorios/pdf', [RelatorioController::class, 'exportarPdf'])->name('admin.relatorios.pdf')->middleware('tipo:gerente,bibliotecario');

require __DIR__.'/auth.php';

// Notifications
use App\Http\Controllers\NotificationController;
Route::middleware(['auth:web,membro'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-read');
});
