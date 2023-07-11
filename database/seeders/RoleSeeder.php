<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => 1,
            'name' => 'super-admin',
        ]);

        Role::create([
            'id' => 2,
            'name' => 'admin',
        ]);

        Role::create([
            'id' => 3,
            'name' => 'rider',
        ]);

        Role::create([
            'id' => 4,
            'name' => 'generic-user',
        ]);
    }
}
