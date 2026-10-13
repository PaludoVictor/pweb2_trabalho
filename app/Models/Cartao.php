<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartao extends Model
{
    use HasFactory;

    protected $table = 'cartoes';

    protected $fillable = [
        'nome_cartao',
        'bandeira',
        'limite_credito',
        'dia_vencimento',
        'imagem',
        'conta_id',
    ];

    protected $casts = [
        'limite_credito' => 'float',
        'dia_vencimento' => 'integer',
        'conta_id' => 'integer',
    ];

    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }
}

