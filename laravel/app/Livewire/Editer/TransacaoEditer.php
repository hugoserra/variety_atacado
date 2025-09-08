<?php

namespace App\Livewire\Editer;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Transacoes;
use Illuminate\Support\Facades\Auth;
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
    public bool $verificada;
    public $valor = 0;

    public function mount()
    {
        if (Auth::user()->role == 'admin') {
            $this->clientes = Cliente::get();
            $this->fornecedores = Fornecedor::get();
        } else {
            $cliente = Cliente::where('nome', Auth::user()->name)->first();
            $fornecedor = Fornecedor::where('nome', Auth::user()->name)->first();
            if ($cliente)
                $this->clientes = [$cliente];
            if ($fornecedor)
                $this->fornecedores = [$fornecedor];
            $this->cliente_id = $cliente ? $cliente->id : null;
            $this->fornecedor_id = $fornecedor ? $fornecedor->id : null;
        }
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
            'verificada' => 'nullable',
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
