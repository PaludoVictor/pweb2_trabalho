<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
