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
            ->withPivot('preco_paraguai')
            ->withPivot('preco_chegada')
            ->withPivot('preco_venda')
            ->withPivot('observacao')
            ->withTimestamps();
    }

    public function scopeSearch($query, $value)
    {
        $query->where('nome', 'like', "%{$value}%");
    }

    public function getQuantidadePorPedido($pedido_id)
    {
        // $ordem_id = is_array($ordem_id) ? $ordem_id['ordem_id'] : $ordem_id;// se depois de muito tempo achar essa linha, pode apagar
        $pedido = $this->pedidos->where('id', $pedido_id)->first();
        return $pedido ? $pedido->pivot->quantidade_produto : 0;
    }

    public function getObservacaoPedido($pedido_id)
    {
        if($pedido_id)
        return $this->pedidos->where('id', $pedido_id)->first()->pivot->observacao;
    }
}
