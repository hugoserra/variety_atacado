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
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($pedido) 
        {
            self::where('id', "!=", $pedido->id)->increment('sort');
        });
        static::saved(function ($pedido) 
        {
            if($pedido->isDirty('tipo_frete'))
                $pedido->calcularTransacao(false);
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
            ->withPivot('preco_paraguai_dolar')
            ->withPivot('preco_paraguai')
            ->withPivot('preco_chegada')
            ->withPivot('preco_venda')
            ->withPivot('porcentagem_frete')
            ->withPivot('porcentagem_lucro')
            ->withPivot('observacao')
            ->withTimestamps();
    }

    public function calcularTransacao($update = true)
    {
        $preco_total_paraguai = 0;
        $preco_total_chegada = 0;
        $preco_total_venda = 0;
        
        foreach ($this->produtos as $produto) {
            $preco_total_paraguai += $produto->pivot->preco_paraguai;
            $preco_total_chegada += $produto->pivot->preco_chegada;
            $preco_total_venda += $produto->pivot->preco_venda;
        }

        Transacoes::where('pedido_id', $this->id)->delete();

        Transacoes::create([
            'cliente_id' => $this->cliente->id,
            'pedido_id' => $this->id,
            'descricao' => "Transação Pedido #{$this->id}: Receber do Cliente R$ {$preco_total_venda}",
            'valor' => -$preco_total_venda,
        ]);

        if($this->tipo_frete == 'pago pelo freteiro')
            Transacoes::create([
                'fornecedor_id' => $this->fornecedor->id,
                'pedido_id' => $this->id,
                'descricao' => "Transação Pedido #{$this->id}: Pagar ao Fornecedor R$ {$preco_total_chegada}",
                'valor' => $preco_total_chegada,
            ]);
        else if($this->tipo_frete == 'pago pelo comprador')
            Transacoes::create([
                'fornecedor_id' => $this->fornecedor->id,
                'pedido_id' => $this->id,
                'descricao' => "Transação Pedido #{$this->id}: Pagar ao Fornecedor R$ " . $preco_total_chegada - $preco_total_paraguai,
                'valor' => $preco_total_chegada - $preco_total_paraguai,
            ]);

        if($update)
        $this->update(['preco_total_chegada' => $preco_total_chegada, 'preco_total_venda' => $preco_total_venda, 'lucro' => $preco_total_venda - $preco_total_chegada]);
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
