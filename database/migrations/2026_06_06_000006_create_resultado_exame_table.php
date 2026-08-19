<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultadoExameTable extends Migration
{
    public function up()
    {
        Schema::create('resultado_exame', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pedido_exame_id');
            $table->unsignedBigInteger('tecnico_id');        // laboratorista (users.id)
            $table->text('resultado');                        // texto do resultado
            $table->string('ficheiro_path')->nullable();      // caminho do PDF/imagem
            $table->date('data_resultado');
            $table->timestamps();

            $table->foreign('pedido_exame_id')->references('id')->on('pedido_exame')->onDelete('cascade');
            $table->foreign('tecnico_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resultado_exame');
    }
}
