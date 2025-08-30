<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['importado', 'nacional'])->default('importado');
            $table->enum('tipo_frete', ['pago pelo freteiro', 'pago pelo comprador'])->default('pago pelo freteiro');
            $table->decimal('preco_produto', 10, 2);
            $table->decimal('preco_produto_dolar', 10, 2);
            $table->decimal('preco_custo', 10, 2);
            $table->decimal('preco_venda_minimo', 10, 2);
            $table->decimal('porcentagem_frete', 5, 2);
            $table->decimal('porcentagem_lucro', 5, 2);
            $table->integer('quantidade_estoque')->default(1);
            $table->text('link_compras_paraguai')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produtos');
    }
};
