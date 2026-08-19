<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSenhaChamadaToEpisodioTable extends Migration
{
    public function up()
    {
        Schema::table('episodio', function (Blueprint $table) {
            // Número de senha sequencial do dia (ex: A001, A002...)
            $table->string('senha', 10)->nullable()->after('urgente');
            // Timestamp da última chamada pelo médico
            $table->timestamp('chamado_em')->nullable()->after('senha');
        });
    }

    public function down()
    {
        Schema::table('episodio', function (Blueprint $table) {
            $table->dropColumn(['senha', 'chamado_em']);
        });
    }
}
