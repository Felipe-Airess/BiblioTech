<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Para ativar o soft delete

class Livros extends Model
{

    // Lista fixa de categorias para dropdown
    public const CATEGORIAS = [
        'Romance',
        'Aventura',
        'Fantasia',
        'Ficção Científica',
        'Biografia',
        'História',
        'Autoajuda',
        'Didático',
        'Terror',
        'Poesia',
        'HQ/Comic',
        'Outros',
    ];
    use HasFactory, SoftDeletes; // Para ativar o soft delete

    // Liberamos todos os campos para inserção no banco
    protected $fillable = [
        'titulo',
        'autor_id',
        'isbn',
        'e_bestseller',
        'capa',
        'categoria',       
        'quantidade',      
        'data_publicacao', 
        'sinopse',
        'editora',
        'paginas',
        'preview'
    ];

    // Converte os dados automaticamente para facilitar a nossa vida
    protected $casts = [
        'e_bestseller'    => 'boolean',
        'quantidade'      => 'integer',
        'data_publicacao' => 'date',
        'paginas'         => 'integer',
    ];

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'livro_id');
    }

    public function comentarioDe($userId, $membroId)
    {
        return $this->comentarios()
            ->where(function ($q) use ($userId, $membroId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                }

                if ($membroId) {
                    // If user_id was added, keep OR for membro_id, otherwise simple where
                    if ($userId) {
                        $q->orWhere('membro_id', $membroId);
                    } else {
                        $q->where('membro_id', $membroId);
                    }
                }
            })
            ->first();
    }
}