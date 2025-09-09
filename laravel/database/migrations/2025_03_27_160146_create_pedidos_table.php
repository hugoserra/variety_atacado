<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores')->onDelete('set null');
            $table->enum('status', ['pendente', 'em andamento', 'finalizado', 'cancelado'])->default('pendente');
            $table->enum('tipo_frete', ['pago pelo freteiro', 'pago pelo comprador', 'pago pelo cliente'])->default('pago pelo freteiro');
            $table->text('observacao')->nullable();
            $table->decimal('cotacao_dolar', 10, 2);
            $table->decimal('preco_total_chegada', 10, 2)->default(0);
            $table->decimal('preco_total_venda', 10, 2)->default(0);
            $table->decimal('lucro', 10, 2)->default(0);
            $table->integer('sort')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};
