<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriagemTable extends Migration
{
    public function up()
    {
        Schema::create('triagem', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('episodio_id');
            $table->string('pressao_arterial', 20)->nullable();  // ex: "120/80"
            $table->decimal('temperatura', 4, 1)->nullable();    // ex: 37.5
            $table->decimal('peso', 5, 2)->nullable();            // kg
            $table->decimal('altura', 5, 2)->nullable();          // cm
            $table->integer('frequencia_cardiaca')->nullable();   // bpm
            $table->integer('frequencia_respiratoria')->nullable(); // rpm
            $table->integer('saturacao_oxigenio')->nullable();    // %
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->foreign('episodio_id')->references('id')->on('episodio')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('triagem');
    }
}
