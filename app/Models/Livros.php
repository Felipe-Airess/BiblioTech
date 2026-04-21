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
}