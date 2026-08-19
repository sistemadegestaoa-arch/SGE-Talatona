<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodioTable extends Migration
{
    public function up()
    {
        Schema::create('episodio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('triagem_user_id');  // utilizador da triagem
            $table->date('data');
            $table->enum('estado', [
                'em_espera',       // triagem feita, aguarda médico
                'em_consulta',     // médico abriu
                'aguarda_exame',   // médico pediu exame, aguarda resultado
                'concluido',       // consulta encerrada
            ])->default('em_espera');
            $table->timestamps();

            $table->foreign('paciente_id')->references('id')->on('paciente')->onDelete('cascade');
            $table->foreign('triagem_user_id')->references('id')->on('users');

            // Um paciente só tem um episódio por dia
            $table->unique(['paciente_id', 'data']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('episodio');
    }
}
