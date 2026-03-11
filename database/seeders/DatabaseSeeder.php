<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Chama o Seeder do Admin para rodar
        $this->call([
            AdminSeeder::class,
        ]);
    }
}