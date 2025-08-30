<?php

namespace App\Livewire\Editer;

use App\Models\Fornecedor;
use Livewire\Attributes\On;
use Livewire\Component;

class FornecedorEditer extends Component
{
    public $id;
    public $nome;
    public $telefone;
    public $porcentagem;

    #[On('editar-fornecedor')]
    public function editer_show($fornecedor_id)
    {
        $fornecedor = Fornecedor::findOrFail($fornecedor_id);
        $this->fill($fornecedor);
        $this->modal('editar-fornecedor')->show();
    }

    public function editar()
    {
        $validated = $this->validate([
            'nome' => 'required',
            'telefone' => 'required|min:3',
            'porcentagem' => 'required',
        ]);
        Fornecedor::findOrFail($this->id)->update($validated);
        $this->dispatch('fornecedor-saved');
        $this->modal('editar-fornecedor')->close();
        $this->dispatch('updated-popup', 'Fornecedor Salvo!');
    }

    public function render()
    {
        return view('livewire.editer.fornecedor-editer');
    }
}
