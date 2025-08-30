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
        'tipo',
        'tipo_frete',
        'preco_custo',
        'preco_produto',
        'preco_produto_dolar',
        'preco_venda',
        'porcentagem_frete',
        'porcentagem_lucro',
        'comissao_vendedor',
        'preco_final',
        'quantidade_estoque',
        'quantidade_produto',
        'link_compras_paraguai'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($produto) {
            $produto->calcularPrecoProdutoReais();
            $produto->calcularPrecoCusto();
            $produto->calcularPrecoVendaMinimo();
        });
        // static::updated(function ($produto) {
        //     if($produto->isDirty('preco_produto') || $produto->isDirty('porcentagem_lucro') || $produto->isDirty('porcentagem_frete'))
        //     CalcService::recalcularPedidosEOrdens($produto);
        // });
        // static::deleted(function ($produto) {
        //     CalcService::recalcularPedidosEOrdens($produto);
        // });
    }

    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class)
            ->using(PedidoProduto::class)
            ->withPivot('quantidade_produto')
            ->withPivot('comissao_vendedor')
            ->withPivot('preco_final')
            ->withPivot('status_estoque')
            ->withPivot('observacao')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'produto_user')
            ->using(Precificacao::class)
            ->withPivot('preco_final')
            ->withTimestamps();
    }

    public function ordens(): BelongsToMany
    {
        return $this->belongsToMany(Ordem::class)
            ->using(OrdemProduto::class)
            ->withPivot('quantidade_produto')
            ->withTimestamps();
    }

    public function scopeSearch($query, $value)
    {
        $query->where('nome', 'like', "%{$value}%")
              ->orWhere('quantidade_estoque', 'like', "%{$value}%")
              ->orWhere('preco_custo', 'like', "%{$value}%")
              ->orWhere('preco_produto', 'like', "%{$value}%")
              ->orWhere('preco_venda_minimo', 'like', "%{$value}%")
              ->orWhere('porcentagem_frete', 'like', "%{$value}%")
              ->orWhere('porcentagem_lucro', 'like', "%{$value}%")
              ->orWhere('tipo', 'like', "%{$value}%");
    }

    public function getQuantidadePorOrdem($ordem_id)
    {
        // $ordem_id = is_array($ordem_id) ? $ordem_id['ordem_id'] : $ordem_id;// se depois de muito tempo achar essa linha, pode apagar
        $ordem = $this->ordens->where('id', $ordem_id)->first();
        return $ordem ? $ordem->pivot->quantidade_produto : 0;
    }

    public function getQuantidadePorPedido($pedido_id)
    {
        // $ordem_id = is_array($ordem_id) ? $ordem_id['ordem_id'] : $ordem_id;// se depois de muito tempo achar essa linha, pode apagar
        $pedido = $this->pedidos->where('id', $pedido_id)->first();
        return $pedido ? $pedido->pivot->quantidade_produto : 0;
    }

    public function getComissaoVendedorPorPedido($pedido_id, $total_qtd = false)
    {
        $pedido = $this->pedidos->where('id', $pedido_id)->first();
        $precificacao = $this->users->where('id', $pedido->user_id)->first()?->pivot;
        if ($pedido->pivot?->comissao_vendedor !== null || $precificacao === null)
        {
            if($total_qtd)
                return round($pedido ? $pedido->pivot->comissao_vendedor * $pedido->pivot->quantidade_produto : 0, 2);
            else
                return round($pedido ? $pedido->pivot->comissao_vendedor : 0, 2);
        }
        else
        {
            if($total_qtd)
                return round(($precificacao->preco_final - $this->preco_venda_minimo) * $pedido->pivot->quantidade_produto, 2);
            else
                return round($precificacao->preco_final - $this->preco_venda_minimo, 2);
        }
    }

    public function getPrecoFinalPorPedido($pedido_id)
    {
        $pedido = $this->pedidos->where('id', $pedido_id)->first();
        $precificacao = $this->users->where('id', $pedido->user_id)->first()?->pivot;

        if($pedido->pivot?->comissao_vendedor !== null || $precificacao === null)
        {
            if($pedido->pivot?->preco_final)
                return round($pedido->pivot?->preco_final , 2);
            else
                return round($pedido ? $this->preco_venda_minimo + $pedido->pivot->comissao_vendedor : 0 , 2);
        }
        else
            return $precificacao->preco_final;
    }

    public function getPrecoFinalVendedor()
    {
        $precificacao = $this->users->where('id', Auth::user()['id'])->first()?->pivot;
        return $precificacao->preco_final;
    }

    public function getObservacaoPedido($pedido_id)
    {
        if($pedido_id)
        return $this->pedidos->where('id', $pedido_id)->first()->pivot->observacao;
    }

    public function getObservacaoOrdem($ordem_id)
    {
        $observacao_ordem = "| ";
        $pedido_ids_produto_ordem = OrdemProduto::where('ordem_id', $ordem_id)->where('produto_id', $this->id)->first()['pedido_ids'];

        if($pedido_ids_produto_ordem)
        {
            foreach ($pedido_ids_produto_ordem as $pedido_id_produto_ordem) 
            {
                $observacao_ordem .= "Pedido #{$pedido_id_produto_ordem}: " . $this->getObservacaoPedido($pedido_id_produto_ordem) . " | ";
            }
            return $observacao_ordem;
        }
        return "Nenhuma";
    }

    public function calcularPrecoCusto()
    {
        $this->preco_custo = round($this->preco_produto + ($this->preco_produto * $this->porcentagem_frete / 100), 2);
    }

    public function calcularPrecoProdutoReais()
    {
        $this->preco_produto = round($this->preco_produto_dolar * CotacaoDolar::getDolarTurismo(), 2);
    }

    public function calcularPrecoVendaMinimo()
    {
        $this->preco_venda_minimo = round($this->preco_produto * (1+($this->porcentagem_lucro + $this->porcentagem_frete)/100) , 2);
        $this->recalcularComissoesPedidos();
    }

    public function recalcularComissoesPedidos()
    {
        foreach($this->pedidos as $pedido)
        {
            if($pedido->pivot->preco_final)
            {
                $pedido->produtos()->updateExistingPivot($this->id, [
                    'comissao_vendedor' => $pedido->pivot->preco_final - $this->preco_venda_minimo
                ]);
            }
        }
    }

    public function getSimulacaoParcelamentoCartao($preco_final = null, $qtd_parcelas)
    {
        if($preco_final == null)
            $preco_final = $this->users->where('id', Auth::user()['id'])->first()?->pivot->preco_final;

        $taxa_maquininha = Configs::where('nome', 'taxa_maquininha')->first()['valor'];
        
        return round($preco_final * ((pow(1 + $taxa_maquininha / 100, $qtd_parcelas) * $taxa_maquininha / 100) / (pow(1 + $taxa_maquininha / 100, $qtd_parcelas) - 1)), 2);
    }
}
