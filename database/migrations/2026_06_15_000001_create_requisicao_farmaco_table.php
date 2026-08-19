<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisicaoFarmacoTable extends Migration
{
    public function up()
    {
        Schema::create('requisicao_farmaco', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('solicitante_id');   // técnico de laboratório
            $table->enum('estado', ['pendente', 'atendida', 'rejeitada'])->default('pendente');
            $table->text('observacao')->nullable();
            $table->unsignedBigInteger('atendido_por')->nullable(); // técnico da farmácia
            $table->timestamp('atendido_em')->nullable();
            $table->timestamps();
        });

        Schema::create('requisicao_farmaco_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('requisicao_farmaco_id');
            $table->unsignedInteger('produto_id');
            $table->integer('quantidade')->default(1);
            $table->string('observacao_item')->nullable();
            $table->timestamps();

            $table->foreign('requisicao_farmaco_id')
                  ->references('id')->on('requisicao_farmaco')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requisicao_farmaco_item');
        Schema::dropIfExists('requisicao_farmaco');
    }
}
