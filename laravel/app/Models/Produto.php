<?php

namespace App\Models;

use App\Services\CalcService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'tipo_frete',
        'quantidade_produto',
        'preco_paraguai'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($produto) {
        });
    }

    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class)
            ->using(PedidoProduto::class)
            ->withPivot('quantidade_produto')
            ->withPivot('preco_paraguai_dolar')
            ->withPivot('preco_paraguai')
            ->withPivot('preco_chegada')
            ->withPivot('preco_venda')
            ->withPivot('porcentagem_frete')
            ->withPivot('porcentagem_lucro')
            ->withPivot('observacao')
            ->withTimestamps();
    }

    public function scopeSearch($query, $value)
    {
        $query->where('nome', 'like', "%{$value}%");
    }
}
