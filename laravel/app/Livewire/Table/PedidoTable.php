<?php

namespace App\Livewire\Table;

use App\Models\Cliente;
use App\Models\Fornecedor;
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
    public $status = '';

    #[Url(history: true)]
    public $cliente_id = '';

    #[Url(history: true)]
    public $fornecedor_id = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url()]
    public $perPage = 20;
    public $pedidos_selecionados = [];

    public $clientes = [];
    public $fornecedores = [];

    public function mount()
    {
        $this->clientes = Cliente::get();
        $this->fornecedores = Fornecedor::get();
    }

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

    public function gerar_relatorio_cliente()
    {
        if (empty($this->pedidos_selecionados))
            return $this->dispatch('deleted-popup', 'Escolha pelo menos 1 pedido!', 8000);

        if($this->cliente_id == '' || $this->cliente_id == 'todos')
        {
            $this->addError('cliente_id', 'É obrigatório informar Cliente para gerar o relatório!');
            return;
        }

        $this->dispatch('gerar-relatorio-cliente', $this->pedidos_selecionados);
    }

    public function gerar_relatorio_fornecedor()
    {
        if (empty($this->pedidos_selecionados))
            return $this->dispatch('deleted-popup', 'Escolha pelo menos 1 pedido!', 8000);

        if($this->fornecedor_id == '' || $this->fornecedor_id == 'todos')
        {
            $this->addError('fornecedor_id', 'É obrigatório informar um Fornecedor para gerar o relatório!');
            return;
        }
        $this->dispatch('gerar-relatorio-fornecedor', $this->pedidos_selecionados);
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
                ->when($this->status !== 'todos' && $this->status !== '', function ($query) {
                    $query->where('status', $this->status);
                })
                ->when($this->cliente_id !== 'todos' && $this->cliente_id !== '', function ($query) {
                    $query->where('cliente_id', $this->cliente_id);
                })
                ->when($this->fornecedor_id !== 'todos' && $this->fornecedor_id !== '', function ($query) {
                    $query->where('fornecedor_id', $this->fornecedor_id);
                })
                ->when($this->sortBy != 'clientes.nome' && $this->sortBy != 'fornecedores.nome', function ($query) use ($sortBy, $sortDir) {
                    $query->orderBy($sortBy, $sortDir);
                })
                ->when($this->sortBy == 'clientes.nome', function ($query) use ($sortDir) {
                    $query->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
                        ->select('pedidos.*') // garante que apenas os campos de pedidos sejam selecionados
                        ->orderBy('clientes.nome', $sortDir);
                })
                ->when($this->sortBy == 'fornecedores.nome', function ($query) use ($sortDir) {
                    $query->join('fornecedores', 'fornecedores.id', '=', 'pedidos.fornecedor_id')
                        ->select('pedidos.*') // garante que apenas os campos de pedidos sejam selecionados
                        ->orderBy('fornecedores.nome', $sortDir);
                })
                ->paginate($this->perPage)
        ]);
    }
}
