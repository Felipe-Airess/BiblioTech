<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Livros; // Puxando o model de Livro
class LivroController extends Controller
{
    public function create()
    {
        return view('admin.livros.create');
    }
    public function store(Request $request)
    {
        // 1. Validação dos dados e da imagem
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'required|string|unique:livros,isbn', // Garante que não repete ISBN
            'capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Só aceita imagem até 2MB
        ]);

        // 2. Prepara os dados básicos
        $dadosLivro = [
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'isbn' => $request->isbn,
            'e_bestseller' => $request->has('e_bestseller'), // Se marcou o checkbox, vira true (1)
        ];

        // 3. O Pulo do Gato: Salvando a Imagem!
        if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
            // Salva na pasta storage/app/public/capas e guarda o caminho gerado
            $caminhoImagem = $request->capa->store('capas', 'public');
            
            // Adiciona o caminho da imagem na nossa lista de dados para salvar no banco
            $dadosLivro['capa'] = $caminhoImagem;
        }

        // 4. Salva tudo no banco de uma vez só
        Livros::create($dadosLivro);

        return redirect()->back()->with('sucesso', 'Livro cadastrado com sucesso!');
    }
    public function destroy(Request $request, $id)
    {
        $livro = Livros::findOrFail($id);
        $livro->delete(); // Isso vai ativar o soft delete
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

        // 1. Validação (A sua já estava perfeita!)
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'required|string|unique:livros,isbn,' . $livro->id, 
            'capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Prepara os dados básicos (incluindo o checkbox de bestseller)
        $dadosLivro = [
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'isbn' => $request->isbn,
            'e_bestseller' => $request->has('e_bestseller'), 
        ];

        // 3. A Mágica da Atualização da Imagem
        if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
            
            // Se o livro já tinha uma capa antes, nós apagamos ela do disco público!
            if ($livro->capa) {
                Storage::disk('public')->delete($livro->capa);
            }

            // Salva a nova imagem na pasta 'capas' e adiciona o caminho no array
            $dadosLivro['capa'] = $request->capa->store('capas', 'public');
        }

        // 4. Atualiza os dados do livro no banco
        $livro->update($dadosLivro);

        return redirect()->back()->with('sucesso', 'Livro atualizado com sucesso!');
    }
}
