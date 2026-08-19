<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceitaIdToAtendimentoTable extends Migration
{
    public function up()
    {
        Schema::table('atendimento', function (Blueprint $table) {
            // Liga opcionalmente um atendimento a uma receita médica do módulo hospitalar.
            // Nullable — atendimentos manuais (externos) não têm receita associada.
            $table->unsignedBigInteger('receita_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('atendimento', function (Blueprint $table) {
            $table->dropColumn('receita_id');
        });
    }
}
