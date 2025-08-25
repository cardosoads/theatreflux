<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador padrão
        User::firstOrCreate(
            ['email' => 'admin@theatreflux.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Criar usuário comum para teste
        User::firstOrCreate(
            ['email' => 'user@theatreflux.com'],
            [
                'name' => 'Usuário Teste',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuários criados com sucesso!');
        $this->command->info('Admin: admin@theatreflux.com / admin123');
        $this->command->info('User: user@theatreflux.com / user123');
    }
}
