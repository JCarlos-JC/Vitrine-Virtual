<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartamentoIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('departamento_id')->nullable(); // Definindo a coluna como nullable se necessário

            // Definindo a chave estrangeira para o departamento_id
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']); // Excluindo a chave estrangeira
            $table->dropColumn('departamento_id'); // Excluindo a coluna departamento_id
        });
    }
}
