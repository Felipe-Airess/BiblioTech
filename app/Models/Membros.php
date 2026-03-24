<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
class Membros extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * We want the Membros model to behave like a normal User (using "name").
     *
     * To keep the database column as "nome" while allowing Breeze/Jetstream
     * and other code to use "name", we map name to nome via an accessor and
     * a mutator.
     *
     * Adding "name" to fillable makes mass assignment work when request uses
     * name (like the default Breeze register form).
     */
    protected $fillable = [
        'nome',
        'name',
        'email',
        'cpf',
        'telefone',
        'endereco',
        'data_nascimento',
        'tipo_membro',
        'numero_carteirinha',
        'password',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nome'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nome'] = $value;
    }

    /**
     * Attributes hidden when serializing.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting definitions.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function user()
    {
        // Aqui dizemos que este registro pertence ao Model User
        // O Laravel vai usar a coluna 'user_id' da sua tabela 'membros'
        return $this->belongsTo(User::class, 'user_id');
    }

}
