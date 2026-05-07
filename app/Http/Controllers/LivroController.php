<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Livros;
use App\Models\Comentario;
use App\Models\Autor;
use App\Models\Emprestimos;
use App\Models\Membros;

class LivroController extends Controller{
    public function dashboard()
    {
        $livros = Livros::with('autor')->latest()->get();
        $bestsellers = Livros::where('e_bestseller', true)->with('autor')->limit(12)->get();
        $livrosRecentes = Livros::latest()->with('autor')->limit(12)->get();
        $categorias = Livros::distinct()->pluck('categoria');
        $autores = Autor::withCount('livros')->latest()->get();

        $categoriasMaisAcessadas = Emprestimos::join('livros', 'emprestimos.livro_id', '=', 'livros.id')
            ->whereNotNull('livros.categoria')
            ->select('livros.categoria', DB::raw('COUNT(*) as total'))
            ->groupBy('livros.categoria')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $totalLivros = Livros::count();
        $totalMembros = Membros::count();
        $emprestimosAtivos = Emprestimos::whereIn('status', [
            Emprestimos::STATUS_APROVADO,
            Emprestimos::STATUS_RETIRADO,
            Emprestimos::STATUS_EM_USO,
        ])->count();
        $devolucoesVencidas = Emprestimos::whereNull('data_devolucao_real')
            ->where('data_devolucao_prevista', '<', today())
            ->count();

        $emprestimosDoMembro = collect();
        if (auth()->guard('membro')->check()) {
            $emprestimosDoMembro = Emprestimos::with('livro.autor')
                ->where('membro_id', auth()->guard('membro')->id())
                ->whereIn('status', [
                    Emprestimos::STATUS_RETIRADO,
                    Emprestimos::STATUS_EM_USO,
                ])
                ->orderBy('data_devolucao_prevista')
                ->get();
        }

        $recomendados = $this->recomendarParaUsuario();

        return view('dashboard', compact(
            'livros',
            'bestsellers',
            'livrosRecentes',
            'categorias',
            'autores',
            'categoriasMaisAcessadas',
            'totalLivros',
            'totalMembros',
            'emprestimosAtivos',
            'devolucoesVencidas',
            'emprestimosDoMembro',
            'recomendados'
        ));
    }
    /**
     * Recomenda livros para o usuário logado com base nas categorias que ele mais pegou emprestado.
     */
    public function recomendarParaUsuario()
    {
        $user = auth()->user();
        $membro = auth()->guard('membro')->user();
        $membroId = $membro ? $membro->id : ($user ? $user->id : null);
        if (!$membroId) {
            return collect();
        }

        $categorias = \App\Models\Emprestimos::where('membro_id', $membroId)
            ->join('livros', 'emprestimos.livro_id', '=', 'livros.id')
            ->select('livros.categoria')
            ->groupBy('livros.categoria')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(2)
            ->pluck('livros.categoria');

        $jaPegouIds = \App\Models\Emprestimos::where('membro_id', $membroId)
            ->pluck('livro_id');

        $recomendados = \App\Models\Livros::whereIn('categoria', $categorias)
            ->whereNotIn('id', $jaPegouIds)
            ->with('autor')
            ->limit(6)
            ->get();

        return $recomendados;
    }

    public function create()
    {
        $autores = Autor::all();
        return view('admin.livros.create', compact('autores'));
    }

    public function store(Request $request)
    {
        // 1. Validação Completa (Antigos + Novos)
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'autor_id'        => 'required|exists:autores,id',
            'isbn'            => [
                'required',
                'string',
                'unique:livros,isbn',
                'regex:/^[0-9]{3}-[0-9]{2}-[0-9]{3}-[0-9]{4}-[0-9]{1}$/'
            ],
            'capa'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria'       => 'required|string|max:100', // NOVO
            'quantidade'      => 'required|integer|min:0',  // NOVO: min:0 impede quantidade negativa!
            'data_publicacao' => 'required|date',           // NOVO
            'sinopse'         => 'nullable|string',  
            'editora'         => 'nullable|string|max:255', // NOVO
            'paginas'         => 'nullable|integer|min:1', // NOVO
            'preview'         => 'nullable|string', // NOVO
        ], [
            'isbn.regex'  => 'O ISBN deve ter exatamente 13 números no formato 000-00-000-0000-0.',
            'isbn.unique' => 'Este ISBN já está cadastrado no sistema.'
        ]);

        // 2. Prepara TODOS os dados para salvar
        $dadosLivro = [
            'titulo'          => $request->titulo,
            'autor_id'        => $request->autor_id,
            'isbn'            => $request->isbn,
            'e_bestseller'    => $request->has('e_bestseller'),
            'categoria'       => $request->categoria,       // NOVO
            'quantidade'      => $request->quantidade,      // NOVO
            'data_publicacao' => $request->data_publicacao, // NOVO
            'sinopse'         => $request->sinopse,         // NOVO
            'editora'         => $request->editora,         // NOVO
            'paginas'         => $request->paginas,         // NOVO
            'preview'         => $request->preview,         // NOVO
        ];

      
        if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
            $dadosLivro['capa'] = $request->file('capa')->store('capas', 'public');
        }

       
        Livros::create($dadosLivro);

        return redirect()->back()->with('sucesso', 'Livro cadastrado com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        $livro = Livros::findOrFail($id);
        $livro->delete(); 
        return redirect()->back()->with('sucesso', 'Livro removido com sucesso!');
    }

    public function edit($id)
    {
        $livro = Livros::findOrFail($id);
        $autores = Autor::all();
        return view('admin.livros.edit', compact('livro', 'autores'));
    }

    public function update(Request $request, $id)
    {
        $livro = Livros::findOrFail($id);

        // 1. Validação Completa (Antigos + Novos na Edição)
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'autor_id'        => 'required|exists:autores,id',
            'isbn'            => [
                'required',
                'string',
                'unique:livros,isbn,' . $livro->id, // Ignora o próprio livro
                'regex:/^[0-9]{3}-[0-9]{2}-[0-9]{3}-[0-9]{4}-[0-9]{1}$/'
            ],
            'capa'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria'       => 'required|string|max:100', // NOVO
            'quantidade'      => 'required|integer|min:0',  // NOVO
            'data_publicacao' => 'required|date',           // NOVO
            'sinopse'         => 'nullable|string',         // NOVO
            'editora'         => 'nullable|string|max:255', // NOVO
            'paginas'         => 'nullable|integer|min:1',  // NOVO
            'preview'         => 'nullable|string',         // NOVO
        ], [
            'isbn.regex'  => 'O ISBN deve ter exatamente 13 números no formato 000-00-000-0000-0.',
            'isbn.unique' => 'Este ISBN já pertence a outro livro.'
        ]);

        // 2. Prepara os dados atualizados
        $dadosLivro = [
            'titulo'          => $request->titulo,
            'autor_id'        => $request->autor_id,
            'isbn'            => $request->isbn,
            'e_bestseller'    => $request->has('e_bestseller'),
            'categoria'       => $request->categoria,       // NOVO
            'quantidade'      => $request->quantidade,      // NOVO
            'data_publicacao' => $request->data_publicacao, // NOVO
            'sinopse'         => $request->sinopse,         // NOVO
            'editora'         => $request->editora,         // NOVO
            'paginas'         => $request->paginas,         // NOVO
            'preview'         => $request->preview,         // NOVO
        ];

        // 3. Atualiza a Imagem (Apagando a antiga)
        if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
            if ($livro->capa) {
                Storage::disk('public')->delete($livro->capa);
            }
            $dadosLivro['capa'] = $request->file('capa')->store('capas', 'public');
        }

        // 4. Salva as alterações
        $livro->update($dadosLivro);

        return redirect()->back()->with('sucesso', 'Livro atualizado com sucesso!');
    }
    public function show($id)
    {
        $livro = Livros::with(['autor', 'comentarios.user', 'comentarios.membro'])->findOrFail($id);
        $comentarios = $livro->comentarios->sortByDesc('created_at');
        $mediaNota = $livro->comentarios->avg('nota');
        $totalComentarios = $livro->comentarios->count();
        $userId = auth()->id();
        $membroId = auth()->guard('membro')->id();
        $comentarioExistente = $livro->comentarioDe($userId, $membroId);

        // Apenas permitir comentário se o membro já tiver devolvido este livro
        $podeComentar = false;
        if (auth()->guard('membro')->check()) {
            $podeComentar = Emprestimos::where('livro_id', $livro->id)
                ->where('membro_id', $membroId)
                ->whereIn('status', [Emprestimos::STATUS_DEVOLVIDO, Emprestimos::STATUS_ENCERRADO])
                ->exists();
        }

        return view('livros.show', compact('livro', 'comentarios', 'mediaNota', 'totalComentarios', 'comentarioExistente', 'podeComentar'));
    }

    public function storeComentario(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|min:5|max:1000',
        ]);

        $livro = Livros::findOrFail($id);
        $userId = auth()->id();
        $membroId = auth()->guard('membro')->id();
        $comentarioExistente = $livro->comentarioDe($userId, $membroId);

        if ($comentarioExistente) {
            return redirect()->back()->withErrors(['comentario' => 'Voce ja comentou este livro. Edite seu comentario existente.']);
        }

        // Verifica se o usuário/membro já devolveu este livro antes de permitir comentar
        $podeComentar = false;
        if (auth()->guard('membro')->check()) {
            $podeComentar = Emprestimos::where('livro_id', $livro->id)
                ->where('membro_id', $membroId)
                ->whereIn('status', [Emprestimos::STATUS_DEVOLVIDO, Emprestimos::STATUS_ENCERRADO])
                ->exists();
        } elseif (auth()->check()) {
            $podeComentar = Emprestimos::where('livro_id', $livro->id)
                ->where('user_id', $userId)
                ->whereIn('status', [Emprestimos::STATUS_DEVOLVIDO, Emprestimos::STATUS_ENCERRADO])
                ->exists();
        }

        if (! $podeComentar) {
            return redirect()->back()->withErrors(['comentario' => 'Só é possível comentar livros que você já devolveu.']);
        }
        $comentario = new Comentario();
        $comentario->livro_id = $livro->id;
        $comentario->nota = $request->nota;
        $comentario->comentario = $request->comentario;

        if (auth()->guard('membro')->check()) {
            $comentario->membro_id = auth()->guard('membro')->id();
        } elseif (auth()->check()) {
            $comentario->user_id = auth()->id();
        } else {
            abort(403);
        }

        $comentario->save();

        return redirect()->back()->with('success', 'Comentario enviado com sucesso.');
    }

    public function updateComentario(Request $request, $livroId, $comentarioId)
    {
        $request->validate([
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|min:5|max:1000',
        ]);

        $comentario = Comentario::where('livro_id', $livroId)->findOrFail($comentarioId);
        $userId = auth()->id();
        $membroId = auth()->guard('membro')->id();

        $isOwner = ($membroId && $comentario->membro_id === $membroId)
            || ($userId && $comentario->user_id === $userId);

        if (!$isOwner) {
            abort(403);
        }

        $comentario->nota = $request->nota;
        $comentario->comentario = $request->comentario;
        $comentario->save();

        return redirect()->back()->with('success', 'Comentario atualizado com sucesso.');
    }

    public function destroyComentario($livroId, $comentarioId)
    {
        $comentario = Comentario::where('livro_id', $livroId)->findOrFail($comentarioId);
        $userId = auth()->id();
        $membroId = auth()->guard('membro')->id();

        $isOwner = ($membroId && $comentario->membro_id === $membroId)
            || ($userId && $comentario->user_id === $userId);

        if (!$isOwner) {
            abort(403);
        }

        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario removido com sucesso.');
    }
}