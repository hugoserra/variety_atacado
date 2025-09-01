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
    protected $fillable = ['produto_id', 'status', 'produtos', 'quantidade_produto', 'observacao', 'cliente_id', 'fornecedor_id', 'cotacao_dolar'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($pedido) 
        {
        });
        static::saving(function ($pedido) 
        {
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

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class)
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
        $query->where('status', 'like', "%{$value}%")
            ->orWhereHas('fornecedor', function ($query) use ($value) {
                $query->where('nome', 'like', "%$value%");
                $query->orWhere('telefone', 'like', "%$value%");
            })
            ->orWhereHas('cliente', function ($query) use ($value) {
                $query->where('nome', 'like', "%$value%");
                $query->orWhere('endereco', 'like', "%$value%");
                $query->orWhere('telefone', 'like', "%$value%");
            });
    }
}
