<?php

namespace App\Livewire\Maker;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ClienteMaker extends Component
{
    public $nome;
    public $telefone;
    public $endereco;
    public $user_id;

    #[On('novo-cliente')]
    public function maker_show()
    {
        $this->modal('novo-cliente')->show();
    }

    public function criar()
    {
        $this->user_id = Auth::user()['id'];
        $validated = $this->validate([
            'user_id' => 'required',
            'nome' => 'required|min:3',
            'telefone' => 'required|min:3',
            'endereco' => 'required|min:3',
        ]);
        Cliente::create($validated);
        $this->dispatch('cliente-saved');
        $this->modal('novo-cliente')->close();
        $this->dispatch('saved-popup', 'Novo Cliente Criado!');
    }
    
    public function render()
    {
        return view('livewire.maker.cliente-maker');
    }
}
