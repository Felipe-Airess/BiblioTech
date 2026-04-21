<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('livros', 'preview')) {
            Schema::table('livros', function (Blueprint $table) {
                $table->text('preview')->nullable();
            });
        }

        if (Schema::hasColumn('livros', 'autor_id') && ! $this->hasAutorForeignKey()) {
            Schema::table('livros', function (Blueprint $table) {
                $table->foreign('autor_id')->references('id')->on('autores')->onDelete('set null');
            });
        }
    }

    private function hasAutorForeignKey(): bool
    {
        $database = DB::getDatabaseName();

        $constraint = DB::table('information_schema.table_constraints')
            ->where('constraint_schema', $database)
            ->where('table_name', 'livros')
            ->where('constraint_name', 'livros_autor_id_foreign')
            ->where('constraint_type', 'FOREIGN KEY')
            ->first();

        return (bool) $constraint;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->hasAutorForeignKey()) {
            Schema::table('livros', function (Blueprint $table) {
                $table->dropForeign(['autor_id']);
            });
        }

        if (Schema::hasColumn('livros', 'preview')) {
            Schema::table('livros', function (Blueprint $table) {
                $table->dropColumn('preview');
            });
        }
    }
};
