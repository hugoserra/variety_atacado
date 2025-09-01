<?php

namespace App\Livewire\Editer;

use App\Models\Cliente;
use Livewire\Attributes\On;
use Livewire\Component;

class ClienteEditer extends Component
{
    public $id;
    public $nome;
    public $telefone;
    public $endereco;
    public $porcentagem_frete = 0;
    public $porcentagem_lucro = 0;

    #[On('editar-cliente')]
    public function editer_show($cliente_id)
    {
        $cliente = Cliente::findOrFail($cliente_id);
        $this->fill($cliente);
        $this->modal('editar-cliente')->show();
    }

    public function editar()
    {
        $validated = $this->validate([
            'nome' => 'required|min:3',
            'telefone' => 'required|min:3',
            'endereco' => 'required|min:3',
            'porcentagem_frete' => 'required|numeric|min:0|max:100',
            'porcentagem_lucro' => 'required|numeric|min:0|max:100',
        ]);
        Cliente::findOrFail($this->id)->update($validated);
        $this->dispatch('cliente-saved');
        $this->modal('editar-cliente')->close();
        $this->dispatch('updated-popup', 'Cliente Salvo!');
    }

    public function render()
    {
        return view('livewire.editer.cliente-editer');
    }
}
