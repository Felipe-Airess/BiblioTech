<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Livros;

class LivroController extends Controller
{
    public function create()
    {
        return view('admin.livros.create');
    }

    public function store(Request $request)
    {
        // 1. Validação Completa (Antigos + Novos)
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'autor'           => 'required|string|max:255',
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
            'sinopse'         => 'nullable|string',         // NOVO: nullable porque você definiu assim no banco
        ], [
            'isbn.regex'  => 'O ISBN deve ter exatamente 13 números no formato 000-00-000-0000-0.',
            'isbn.unique' => 'Este ISBN já está cadastrado no sistema.'
        ]);

        // 2. Prepara TODOS os dados para salvar
        $dadosLivro = [
            'titulo'          => $request->titulo,
            'autor'           => $request->autor,
            'isbn'            => $request->isbn,
            'e_bestseller'    => $request->has('e_bestseller'),
            'categoria'       => $request->categoria,       // NOVO
            'quantidade'      => $request->quantidade,      // NOVO
            'data_publicacao' => $request->data_publicacao, // NOVO
            'sinopse'         => $request->sinopse,         // NOVO
        ];

        // 3. Salva a Imagem
        if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
            $dadosLivro['capa'] = $request->file('capa')->store('capas', 'public');
        }

        // 4. Salva no banco
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
        return view('admin.livros.edit', compact('livro'));
    }

    public function update(Request $request, $id)
    {
        $livro = Livros::findOrFail($id);

        // 1. Validação Completa (Antigos + Novos na Edição)
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'autor'           => 'required|string|max:255',
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
        ], [
            'isbn.regex'  => 'O ISBN deve ter exatamente 13 números no formato 000-00-000-0000-0.',
            'isbn.unique' => 'Este ISBN já pertence a outro livro.'
        ]);

        // 2. Prepara os dados atualizados
        $dadosLivro = [
            'titulo'          => $request->titulo,
            'autor'           => $request->autor,
            'isbn'            => $request->isbn,
            'e_bestseller'    => $request->has('e_bestseller'),
            'categoria'       => $request->categoria,       // NOVO
            'quantidade'      => $request->quantidade,      // NOVO
            'data_publicacao' => $request->data_publicacao, // NOVO
            'sinopse'         => $request->sinopse,         // NOVO
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
}