<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceitaTable extends Migration
{
    public function up()
    {
        Schema::create('receita', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consulta_id');
            $table->unsignedBigInteger('medico_id');
            $table->enum('estado', ['pendente', 'dispensada'])->default('pendente');
            $table->text('observacao')->nullable();
            $table->date('data');
            $table->timestamps();

            $table->foreign('consulta_id')->references('id')->on('consulta')->onDelete('cascade');
            $table->foreign('medico_id')->references('id')->on('users');
        });

        Schema::create('receita_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receita_id');
            $table->unsignedInteger('produto_id');    // tabela produto existente
            $table->string('dose')->nullable();        // ex: "500mg"
            $table->string('frequencia')->nullable();  // ex: "3x ao dia"
            $table->string('duracao')->nullable();     // ex: "7 dias"
            $table->integer('quantidade');
            $table->timestamps();

            $table->foreign('receita_id')->references('id')->on('receita')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receita_item');
        Schema::dropIfExists('receita');
    }
}
