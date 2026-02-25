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
        
        Schema::create('logs_auditoria', function (Blueprint $table) {
            // Chave primária
            $table->id('id');
            
            // Chave Estrangeira ligando com a tabela de usuários
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            
            // Colunas da auditoria
            $table->string('acao'); // Ex: 'EXCLUIU_LIVRO'
            $table->string('tabela_afetada'); // Ex: 'LIVROS'
            $table->dateTime('data_hora'); // Quando aconteceu
            
           
            $table->timestamp('data_criacao')->nullable();
            $table->timestamp('data_atualizacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_auditoria');
    }
};