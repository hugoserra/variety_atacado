<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = ['nome', 'endereco', 'telefone', 'porcentagem_frete', 'porcentagem_lucro'];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

    public function scopeSearch($query, $value)
    {
        $query->where('nome', 'like', "%{$value}%")
            ->orWhere('telefone', 'like', "%{$value}%")
            ->orWhere('endereco', 'like', "%{$value}%");
    }

    public function transacoes(): HasMany
    {
        return $this->hasMany(Transacoes::class, 'cliente_id');
    }

    public function calcularSaldo(): float
    {
        return $this->transacoes()->sum('valor');
    }
}
