<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trabalhos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50);
            $table->string('autor',255);
            $table->string('titulo', 255);
            $table->string('orientador', 100);
            $table->text('resumo');
            $table->text('abstract');
            $table->string('palavras_chave'); 
            $table->string('idioma', 50);
            $table->string('pais', 100);
            $table->string('instituicao', 255);
            $table->string('departamento', 255);
            $table->string('uri')->unique();
            $table->date('data_documento');
            $table->text('descricao')->nullable();
            $table->string('arquivo'); // Arquivo upload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabalhos');
    }
};
