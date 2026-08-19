<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtendimentoTable extends Migration
{
    public function up()
    {
        Schema::create('atendimento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('requisicao_id')->nullable();
            $table->string('utente');
            $table->string('processo')->nullable();
            $table->unsignedInteger('departamento_id');
            $table->unsignedBigInteger('users_id');
            $table->text('observacao')->nullable();
            $table->date('data');
            $table->timestamps();
        });

        Schema::create('atendimento_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('atendimento_id');
            $table->unsignedInteger('produto_id');
            $table->unsignedInteger('lote_id');
            $table->integer('quantidade');
            $table->timestamps();

            $table->foreign('atendimento_id')->references('id')->on('atendimento')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('atendimento_item');
        Schema::dropIfExists('atendimento');
    }
}
