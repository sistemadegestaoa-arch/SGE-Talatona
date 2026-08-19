<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBloqueadoToProdutoTable extends Migration
{
    public function up()
    {
        Schema::table('produto', function (Blueprint $table) {
            $table->boolean('bloqueado')->default(false)->after('stokminimo');
            $table->string('motivo_bloqueio')->nullable()->after('bloqueado');
            $table->unsignedBigInteger('bloqueado_por')->nullable()->after('motivo_bloqueio');
            $table->timestamp('bloqueado_em')->nullable()->after('bloqueado_por');
        });
    }

    public function down()
    {
        Schema::table('produto', function (Blueprint $table) {
            $table->dropColumn(['bloqueado', 'motivo_bloqueio', 'bloqueado_por', 'bloqueado_em']);
        });
    }
}
