<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'superadmin1',
            'name' => 'Super Admin',
            'email' => 'superadmin@superadmin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role_id' => Role::SUPERADMIN_ROLE,
        ]);

        User::create([
            'username' => 'admin1',
            'name' => 'The Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role_id' => Role::ADMIN_ROLE,
        ]);

        User::create([
            'username' => 'rider1',
            'name' => 'The Rider',
            'email' => 'rider@rider.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role_id' => Role::RIDER_ROLE,
        ]);

        User::create([
            'username' => 'user1',
            'name' => 'The User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role_id' => Role::USER_ROLE,
        ]);
    }
}
