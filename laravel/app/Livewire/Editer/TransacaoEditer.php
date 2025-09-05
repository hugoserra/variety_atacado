<?php

namespace App\Livewire\Editer;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Transacoes;
use Livewire\Attributes\On;
use Livewire\Component;

class TransacaoEditer extends Component
{
    public $id;
    public $cliente_id;
    public $clientes = [];

    public $fornecedor_id;
    public $fornecedores = [];

    public $descricao;
    public $valor = 0;

    public function mount()
    {
        $this->clientes = Cliente::get();
        $this->fornecedores = Fornecedor::get();
    }
    
    #[On('editar-transacao')]
    public function editer_show($transacao_id)
    {
        $transacao = Transacoes::findOrFail($transacao_id);
        $this->fill($transacao);
        $this->modal('editar-transacao')->show();
    }

    public function editar()
    {
        $validated = $this->validate([
            'cliente_id' => 'nullable|numeric',
            'fornecedor_id' => 'nullable|numeric',
            'descricao' => 'required',
            'valor' => 'required|numeric',
        ]);
        Transacoes::findOrFail($this->id)->update($validated);
        $this->dispatch('transacao-saved');
        $this->modal('editar-transacao')->close();
        $this->dispatch('updated-popup', 'Transação Salva!');
    }

    public function render()
    {
        return view('livewire.editer.transacao-editer');
    }
}
