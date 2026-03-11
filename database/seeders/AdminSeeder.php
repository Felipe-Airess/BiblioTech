<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cria o nosso usuário Gerente/Admin master
        DB::table('users')->insert([
            'email' => 'gerente@bibliotech.com',
            'name'=> 'Gerente',
            'password' => Hash::make('12345678'), // A senha criptografada com segurança
            'tipo_usuario' => 'gerente', // O cargo que definimos no seu DER
            // 'membro_id' pode ficar vazio (null) já que ele é funcionário e não um cliente
        ]);
    }
}