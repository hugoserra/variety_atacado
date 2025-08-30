<?php

namespace App\Livewire\Table;

use App\Models\Ordem;
use App\Models\Produto;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProdutoTable extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $tipo_frete = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url()]
    public $perPage = 20;

    public $ordem;
    public $pedido_id;

    #[On('set-produto-ordem-id')]
    public function set_produt_ordem_id($ordem_id)
    {
        $this->ordem = Ordem::find($ordem_id);
    }

    #[On('set-produto-pedido-id')]
    public function set_produt_pedido_id($pedido_id)
    {
        $this->pedido_id = $pedido_id;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete(Produto $produto)
    {
        $produto->delete();
    }

    public function setSortBy($sortByField)
    {

        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? 'DESC' : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }

    public function desvincularOrdem($produto_id)
    {
        if ($this->ordem->pedidos->count()) return;

        $produto = Produto::findOrFail($produto_id);
        $produto->ordens()->detach($this->ordem->id);
        $this->dispatch('updated-popup', 'Produto Desvinculado!');
    }

    public function desvincularPedido($produto_id)
    {
        $produto = Produto::findOrFail($produto_id);
        $produto->pedidos()->detach($this->pedido_id);
        $this->dispatch('updated-popup', 'Produto Desvinculado!');
    }

    public function setObservacaoProdutoPedido($produto_id, $obs)
    {
        $produto = Produto::findOrFail($produto_id);
        $produto->pedidos()->where('pedidos.id', $this->pedido_id)->first()->pivot->update(['observacao' => $obs]);
    }

    #[On('produto-saved')]
    public function render()
    {
        $ordem_id = $this->ordem?->id;
        $pedido_id = $this->pedido_id;
        $produtos = Produto::search($this->search)
                            ->when($this->tipo_frete !== '', function ($query) {
                                $query->where('tipo_frete', $this->tipo_frete);
                            })
                            ->when($ordem_id, function ($query) use ($ordem_id) {
                                $query->whereHas('ordens', function ($query) use ($ordem_id) {
                                    $query->where('ordem_id', $ordem_id);
                                });
                            })
                            ->when($pedido_id, function ($query) use ($pedido_id) {
                                $query->whereHas('pedidos', function ($query) use ($pedido_id) {
                                    $query->where('pedido_id', $pedido_id);
                                });
                            })
                            ->orderBy($this->sortBy, $this->sortDir)
                            ->paginate($this->perPage);
        return view('livewire.table.produto-table',
        [
            'produtos' => $produtos,
        ]);
    }
}
