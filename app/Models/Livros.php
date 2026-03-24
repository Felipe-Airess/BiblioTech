<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Para ativar o soft delete

class Livros extends Model
{
    use HasFactory, SoftDeletes; // Para ativar o soft delete

    // Liberamos todos os campos para inserção no banco
    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'e_bestseller',
        'capa',
        'categoria',       
        'quantidade',      
        'data_publicacao', 
        'sinopse'          
    ];

    // Converte os dados automaticamente para facilitar a nossa vida
    protected $casts = [
        'e_bestseller'    => 'boolean',
        'quantidade'      => 'integer',
        'data_publicacao' => 'date',
    ];
}