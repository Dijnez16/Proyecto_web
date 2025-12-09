<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@cmdb.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Crear usuario operativo
        User::create([
            'name' => 'Usuario Operativo', 
            'email' => 'operador@cmdb.com',
            'password' => Hash::make('password'),
            'role' => 'operational'
        ]);

        // Crear categorías por defecto
        \App\Models\Category::create(['name' => 'Hardware', 'description' => 'Equipos físicos y componentes']);
        \App\Models\Category::create(['name' => 'Software', 'description' => 'Licencias y aplicaciones']);
        \App\Models\Category::create(['name' => 'Equipo de Red', 'description' => 'Dispositivos de networking']);
        \App\Models\Category::create(['name' => 'Equipo de Cómputo', 'description' => 'Computadoras y laptops']);
        \App\Models\Category::create(['name' => 'Equipo de Telefonía', 'description' => 'Teléfonos y dispositivos de comunicación']);
    }
}