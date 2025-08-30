<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Pedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pedidos';
    protected $fillable = ['ordem_id', 'cliente_id', 'user_id', 'produto_id', 'status', 'comissao_vendedor', 'comissao_paga', 'produtos', 'valor_total', 'quantidade_produto', 'status_estoque', 'observacao'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($pedido) 
        {
            $pedido->user_id = Auth::user()['id'];
        });
        static::saving(function ($pedido) 
        {
            $pedido->calcularValorTotal();
            $pedido->calcularCommissaoTotal();
        });
        static::deleting(function ($pedido) {
            if($pedido->status != "finalizado")
                $pedido->update(['status' => 'cancelado']);
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class)
            ->using(PedidoProduto::class)
            ->withPivot('quantidade_produto')
            ->withPivot('comissao_vendedor')
            ->withPivot('status_estoque')
            ->withTimestamps();
    }

    public function scopeSearch($query, $value)
    {
        $query->where('valor_total', 'like', "%{$value}%")
            ->orWhere('status', 'like', "%{$value}%")
            ->orWhere('comissao_total', 'like', "%{$value}%")
            ->orWhereHas('user', function ($query) use ($value) {
                $query->where('name', 'like', "%$value%");
                $query->orWhere('email', 'like', "%$value%");
            })
            ->orWhereHas('cliente', function ($query) use ($value) {
                $query->where('nome', 'like', "%$value%");
                $query->orWhere('endereco', 'like', "%$value%");
                $query->orWhere('telefone', 'like', "%$value%");
            });
    }

    public function calcularLucroLiquido()
    {
        $this->lucro_liquido = 0;
        foreach ($this->produtos as $produto) {
            $this->lucro_liquido += ($produto->preco_venda_minimo - $produto->preco_custo) * $produto->pivot->quantidade_produto;
        }
        return round($this->lucro_liquido, 2);
    }

    public function calcularValorTotal()
    {
        $this->valor_total = 0;
        foreach ($this->produtos as $produto) 
        {
            $this->valor_total += $produto->getPrecoFinalPorPedido($this->id) * $produto->pivot->quantidade_produto;
        }
        $this->valor_total = round($this->valor_total, 2);
    }

    public function calcularCommissaoTotal($force = false)
    {
        if(!isset($this->created_at) || $this->created_at->gt(Carbon::now()->subMinutes(60)) || $force)
        {
            $this->comissao_total = 0;
            
            foreach ($this->produtos as $produto) 
            {
                $this->comissao_total += $produto->getComissaoVendedorPorPedido($this->id, true);
            }
            $this->comissao_total = round($this->comissao_total, 2);
        }
    }
}
