<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedido_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->integer('quantidade_produto')->default(1);
            $table->decimal('preco_paraguai_dolar', 10, 2)->nullable();
            $table->decimal('preco_paraguai', 10, 2)->nullable();
            $table->decimal('preco_chegada', 10, 2)->nullable();
            $table->decimal('preco_venda', 10, 2)->nullable();
            $table->decimal('porcentagem_frete', 10, 2);
            $table->decimal('porcentagem_lucro', 10, 2);
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produto_pedido');
    }
};
