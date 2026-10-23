<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'cpf',
        'imagem',
        'foto',
        'telefone',
        'email',
        'login',
        'senha',
    ];

    public function setSenhaAttribute(string $value): void
    {
        $this->attributes['senha'] = password_get_info($value)['algo']
            ? $value
            : Hash::make($value);
    }

    /**
     * Accessor para permitir o uso de $usuario->foto de forma intercambiável com $usuario->imagem
     */
    public function getFotoAttribute()
    {
        return $this->attributes['imagem'] ?? null;
    }

    /**
     * Mutator para permitir atribuição direta através de foto
     */
    public function setFotoAttribute($value)
    {
        $this->attributes['imagem'] = $value;
    }
}
