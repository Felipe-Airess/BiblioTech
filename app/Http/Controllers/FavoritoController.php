<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Livros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FavoritoController extends Controller
{
    public function index()
    {
        $membro = auth()->guard('membro')->user();
        $favoritos = collect();

        if (Schema::hasTable('favoritos')) {
            $favoritos = $membro->livrosFavoritos()
                ->with('autor')
                ->orderByPivot('created_at', 'desc')
                ->get();
        }

        return view('membros.favoritos', compact('favoritos'));
    }

    public function toggle(Request $request, Livros $livro)
    {
        if (! Schema::hasTable('favoritos')) {
            return back()->with('erro', 'A tabela de favoritos ainda não existe. Rode as migrations antes de usar este recurso.');
        }

        $membroId = auth()->guard('membro')->id();

        $favorito = Favorito::where('membro_id', $membroId)
            ->where('livro_id', $livro->id)
            ->first();

        if ($favorito) {
            $favorito->delete();

            return back()->with('sucesso', 'Livro removido dos favoritos.');
        }

        Favorito::create([
            'membro_id' => $membroId,
            'livro_id' => $livro->id,
        ]);

        return back()->with('sucesso', 'Livro adicionado aos favoritos.');
    }
}
