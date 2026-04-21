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
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->unsignedBigInteger('autor_id')->nullable();
            $table->string('isbn')->unique();
            $table->boolean('e_bestseller')->default(false); // RN002 [1]
            $table->string('capa')->nullable();
            $table->string('editora')->nullable();
            $table->integer('paginas')->nullable();
            $table->text('preview')->nullable();
            $table->string('categoria');
            $table->integer('quantidade');
            $table->date('data_publicacao');
            $table->string('sinopse')->nullable();
            $table->timestamps();
            $table->softDeletes(); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
