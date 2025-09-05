<?php

namespace App\Livewire\Maker;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Transacoes;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TransacaoMaker extends Component
{
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

    #[On('nova-transacao')]
    public function maker_show()
    {
        $this->modal('nova-transacao')->show();
    }

    public function criar()
    {
        $validated = $this->validate([
            'cliente_id' => 'nullable|numeric',
            'fornecedor_id' => 'nullable|numeric',
            'descricao' => 'required',
            'valor' => 'required|numeric',
        ]);
        if (empty($this->cliente_id) && empty($this->fornecedor_id)) {
            $this->addError('cliente_id', 'É obrigatório informar Cliente ou Fornecedor.');
            $this->addError('fornecedor_id', 'É obrigatório informar Cliente ou Fornecedor.');
            return;
        }
        Transacoes::create($validated);
        $this->dispatch('transacao-saved');
        $this->modal('nova-transacao')->close();
        $this->dispatch('saved-popup', 'Nova Transação Criada!');
    }

    public function render()
    {
        return view('livewire.maker.transacao-maker');
    }
}
