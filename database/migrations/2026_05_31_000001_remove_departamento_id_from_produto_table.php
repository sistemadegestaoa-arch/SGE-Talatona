<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Remove departamento_id da tabela produto.
 *
 * Os produtos passam a ser um catálogo global partilhado por todos os
 * departamentos. O stock por departamento é controlado exclusivamente
 * pela tabela estoque (que já possui departamento_id).
 */
class RemoveDepartamentoIdFromProdutoTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('produto', 'departamento_id')) {
            // Remove a FK com o nome real detectado na BD
            try {
                DB::statement('ALTER TABLE produto DROP FOREIGN KEY produto_ibfk_1');
            } catch (\Exception $e) { /* ignora se não existir */ }

            Schema::table('produto', function (Blueprint $table) {
                $table->dropColumn('departamento_id');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('produto', 'departamento_id')) {
            Schema::table('produto', function (Blueprint $table) {
                $table->unsignedInteger('departamento_id')->nullable()->after('codigo');
            });
        }
    }
}
