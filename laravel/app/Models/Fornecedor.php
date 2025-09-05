<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;
    protected $table = 'fornecedores';
    protected $guarded = [];

    public function transacoes()
    {
        return $this->hasMany(Transacoes::class, 'fornecedor_id');
    }

    public function calcularSaldo(): float
    {
        return $this->transacoes()->sum('valor');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('nome', 'like', "%{$value}%")
            ->orWhere('telefone', 'like', "%{$value}%");
    }
}
