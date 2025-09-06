<?php

namespace App\Livewire\Table;

use App\Models\Transacoes;
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
                ->paginate($this->perPage)
        ]);
    }
}
