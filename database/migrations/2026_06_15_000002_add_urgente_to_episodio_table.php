<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrgenteToEpisodioTable extends Migration
{
    public function up()
    {
        Schema::table('episodio', function (Blueprint $table) {
            $table->boolean('urgente')->default(false)->after('estado');
        });
    }

    public function down()
    {
        Schema::table('episodio', function (Blueprint $table) {
            $table->dropColumn('urgente');
        });
    }
}
