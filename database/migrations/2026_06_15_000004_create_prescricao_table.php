<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescricaoTable extends Migration
{
    public function up()
    {
        Schema::create('prescricao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consulta_id');
            $table->unsignedBigInteger('medico_id');
            $table->text('diagnostico')->nullable();
            $table->text('observacao')->nullable();
            $table->date('data');
            $table->timestamps();

            $table->foreign('consulta_id')->references('id')->on('consulta')->onDelete('cascade');
            $table->foreign('medico_id')->references('id')->on('users');
        });

        Schema::create('prescricao_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prescricao_id');
            $table->string('medicamento');          // nome livre (não ligado ao stock)
            $table->string('forma_farmaceutica')->nullable(); // comprimido, xarope, injectável...
            $table->string('dosagem')->nullable();  // ex: 500mg
            $table->string('dose')->nullable();     // ex: 1 comprimido
            $table->string('frequencia')->nullable();// ex: 3x ao dia
            $table->string('duracao')->nullable();  // ex: 7 dias
            $table->integer('quantidade')->default(1);
            $table->text('instrucoes')->nullable(); // instruções adicionais
            $table->timestamps();

            $table->foreign('prescricao_id')->references('id')->on('prescricao')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescricao_item');
        Schema::dropIfExists('prescricao');
    }
}
