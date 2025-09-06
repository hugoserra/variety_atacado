<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transacoes extends Model
{
    use HasFactory;

    protected $table = 'transacoes';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($transacao) 
        {
            self::where('id', "!=", $transacao->id)->increment('sort');
        });
        static::saving(function ($transacoes) 
        {
        });
        static::deleting(function ($transacoes) {
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function pessoa()
    {
        if ($this->cliente_id) {
            return $this->cliente;
        } elseif ($this->fornecedor_id) {
            return $this->fornecedor;
        }
        return null;
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function scopeSearch($query, $value)
    {
        $query->where('descricao', 'like', "%{$value}%")
            ->orWhere('valor', 'like', "%$value%")
            ->orWhere('created_at', 'like', "%$value%")
            ->orWhereHas('fornecedor', function ($query) use ($value) {
                $query->where('nome', 'like', "%$value%");
                $query->orWhere('telefone', 'like', "%$value%");
            })
            ->orWhereHas('cliente', function ($query) use ($value) {
                $query->where('nome', 'like', "%$value%");
                $query->orWhere('endereco', 'like', "%$value%");
                $query->orWhere('telefone', 'like', "%$value%");
            });
    }
}
