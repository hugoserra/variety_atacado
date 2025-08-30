<?php

namespace App\Livewire\Table;

use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PedidoTable extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $type = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url()]
    public $perPage = 20;


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete(Pedido $pedido)
    {
        if($pedido->status == 'em andamento')
            return $this->dispatch('deleted-popup', "Não é possivel deletar um pedido Em Andamento!", 9000);
        $pedido->delete();
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

    #[On('pedido-saved')]
    public function render()
    {
        $sortBy = $this->sortBy;
        $sortDir = $this->sortDir;

        return view('livewire.table.pedido-table',
        [
            'pedidos' => Pedido::search($this->search)
                ->when($this->type !== '', function ($query) {
                    $query->where('status', $this->type);
                })
                ->when($this->sortBy != 'cliente.nome' && $this->sortBy != 'fornecedor.nome', function ($query) use ($sortBy, $sortDir) {
                    $query->orderBy($sortBy, $sortDir);
                })
                ->when($this->sortBy == 'cliente.nome', function ($query) use ($sortDir) {
                    $query->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
                        ->select('pedidos.*') // garante que apenas os campos de pedidos sejam selecionados
                        ->orderBy('clientes.nome', $sortDir);
                })
                    ->when($this->sortBy == 'fornecedor.nome', function ($query) use ($sortDir) {
                        $query->join('fornecedores', 'fornecedores.id', '=', 'pedidos.fornecedor_id')
                            ->select('pedidos.*') // garante que apenas os campos de pedidos sejam selecionados
                            ->orderBy('fornecedores.nome', $sortDir);
                    })
                ->paginate($this->perPage)
        ]);
    }
}
