<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamePacoteTable extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exame_pacote', function (Blueprint $table) {
            $table->foreignId('exame_id')->constrained()->onDelete('cascade');
            $table->foreignId('pacote_id')->constrained()->onDelete('cascade');

            // Define uma chave primária composta para evitar duplicados
            $table->primary(['exame_id', 'pacote_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exame_pacote');
    }
};
