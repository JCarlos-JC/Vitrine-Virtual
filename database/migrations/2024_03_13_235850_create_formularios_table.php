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
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('curso_id');
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('documento_id');
            $table->string('arquivo'); // Arquivo upload
            $table->boolean('status')->default(true);
            $table->timestamps();

             // Adiciona a chave estrangeira
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('cascade');
            $table->foreign('documento_id')->references('id')->on('documentos')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('formularios');
    }
};
