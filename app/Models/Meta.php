<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';

    protected $fillable = [
        'titulo',
        'valor_meta',
        'valor_atual',
        'data_limite',
        'imagem',
        'conta_id',
        'categoria_id',
    ];

    protected $casts = [
        'valor_meta' => 'float',
        'valor_atual' => 'float',
        'data_limite' => 'date',
        'conta_id' => 'integer',
        'categoria_id' => 'integer',
    ];

    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}

