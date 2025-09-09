<?php

namespace App\Livewire\Table;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Transacoes;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TransacaoTable extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $sortBy = '';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url()]
    public $perPage = 20;
    public $transacoes_selecionadas = [];
    public $pessoas = [];
    public $pessoa_nome = 'todos';

    public function mount()
    {
        $this->pessoas = Cliente::get()->concat(Fornecedor::get())->toArray();
    }

    public function updatedPessoaNome($nome)
    {
        $this->transacoes_selecionadas = []; 
    }

    public function gerar_relatorio_transacoes()
    {
        if (empty($this->transacoes_selecionadas))
            return $this->dispatch('deleted-popup', 'Escolha pelo menos 1 transação!', 8000);

        if ($this->pessoa_nome == '' || $this->pessoa_nome == 'todos') {
            $this->addError('cliente_id', 'É obrigatório informar a pessoa para gerar o relatório!');
            return;
        }
        $this->dispatch('gerar-relatorio-transacoes', $this->transacoes_selecionadas);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete(Transacoes $transacao)
    {
        $transacao->delete();
    }

    public function set_sort($transacao_id, $position)
    {
        $transacao = Transacoes::find($transacao_id);
        if (!$transacao) return;

        $position = max(0, (int)$position);
        $oldSort = $transacao->sort;
        
        if ($position == $oldSort) return;

        if ($position > $oldSort) {
            // Move para baixo: decrementa os que estão entre oldSort+1 e position
            Transacoes::where('sort', '>', $oldSort)
                ->where('sort', '<=', $position)
                ->decrement('sort');
        } else {
            // Move para cima: incrementa os que estão entre position e oldSort-1
            Transacoes::where('sort', '>=', $position)
                ->where('sort', '<', $oldSort)
                ->increment('sort');
        }
        $transacao->sort = $position;
        $transacao->save();
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

    #[On('transacao-saved')]
    public function render()
    {
        return view('livewire.table.transacao-table',
        [
            'transacoes' => Transacoes::search($this->search)
                ->when($this->sortBy, function ($query) {
                    $query->orderBy($this->sortBy, $this->sortDir);
                })
                ->when($this->sortBy == '', function ($query) {
                    $query->orderBy('sort', 'asc')->orderBy('id', 'desc');
                })
                ->when($this->pessoa_nome != 'todos', function ($query) {
                    $query->where(function ($q) {
                        $q->whereHas('cliente', function ($q2) {
                            $q2->where('nome', $this->pessoa_nome);
                        })->orWhereHas('fornecedor', function ($q3) {
                            $q3->where('nome', $this->pessoa_nome);
                        });
                    });
                })
                ->when(Auth::user()['role'] == 'user', function ($query) {
                    $query->where('user_id', Auth::user()['id']);
                })
                ->paginate($this->perPage)
        ]);
    }
}
