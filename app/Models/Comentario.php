<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'livro_id',
        'user_id',
        'membro_id',
        'nota',
        'comentario',
    ];

    protected $casts = [
        'nota' => 'integer',
    ];

    public function livro()
    {
        return $this->belongsTo(Livros::class, 'livro_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function membro()
    {
        return $this->belongsTo(Membros::class, 'membro_id');
    }
}
