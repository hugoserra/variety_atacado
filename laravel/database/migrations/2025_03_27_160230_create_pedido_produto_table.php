<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedido_produto', function (Blueprint $table) {
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->primary(['pedido_id', 'produto_id']);
            $table->enum('status_estoque', ['inicial', 'calculado'])->default('inicial');
            $table->integer('quantidade_produto')->default(1);
            $table->decimal('preco_final', 10, 2)->nullable();
            $table->decimal('comissao_vendedor', 10, 2)->nullable();
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produto_pedido');
    }
};
