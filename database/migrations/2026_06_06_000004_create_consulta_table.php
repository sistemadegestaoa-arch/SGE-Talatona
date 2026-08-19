<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultaTable extends Migration
{
    public function up()
    {
        Schema::create('consulta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('episodio_id');
            $table->unsignedBigInteger('medico_id');   // users.id
            $table->text('diagnostico')->nullable();
            $table->text('observacao')->nullable();
            $table->date('data');
            $table->timestamps();

            $table->foreign('episodio_id')->references('id')->on('episodio')->onDelete('cascade');
            $table->foreign('medico_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('consulta');
    }
}
