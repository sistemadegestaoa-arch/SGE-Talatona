<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoExameTable extends Migration
{
    public function up()
    {
        Schema::create('pedido_exame', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consulta_id');
            $table->unsignedBigInteger('medico_id');         // quem pediu
            $table->string('descricao_exame');               // ex: "Hemograma completo"
            $table->boolean('urgente')->default(false);
            $table->enum('estado', ['pendente', 'concluido'])->default('pendente');
            $table->text('observacao')->nullable();
            $table->date('data_pedido');
            $table->timestamps();

            $table->foreign('consulta_id')->references('id')->on('consulta')->onDelete('cascade');
            $table->foreign('medico_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_exame');
    }
}
