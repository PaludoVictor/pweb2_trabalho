<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nome_categoria',
        'tipo_despesa',
        'limite_orcamento',
        'cor_identificacao'
    ];

    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'categoria_id');
    }
}
