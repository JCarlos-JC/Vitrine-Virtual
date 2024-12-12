<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('gestor')->default(false);
        $table->boolean('docente')->default(false);
        $table->boolean('estudante')->default(false);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('gestor');
        $table->dropColumn('docente');
        $table->dropColumn('estudante');
    });
}

};
