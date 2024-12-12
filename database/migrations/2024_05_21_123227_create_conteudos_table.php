<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConteudosTable extends Migration
{
    public function up()
    {
        Schema::create('conteudos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('arquivo');
            $table->boolean('disponivel')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conteudos');
    }
}
