<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    use HasFactory;

    protected $table = 'favoritos';

    protected $fillable = [
        'membro_id',
        'livro_id',
    ];

    public function membro()
    {
        return $this->belongsTo(Membros::class, 'membro_id');
    }

    public function livro()
    {
        return $this->belongsTo(Livros::class, 'livro_id');
    }
}
