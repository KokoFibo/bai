<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Anton',
            'email' => 'kokonacci@gmail.com',
            'email_verified_at' => now(),
            'username' => 50000,
            'role' => 5,
            'password' => Hash::make('Anton888'), // 123456789
            // 'password' => '$2y$10$7crdZF/aXQJ2bh.QIR/7CO9FhtAz7DrsdIn3w24CTJNxbY6BX/8j2', // 123456789
            'remember_token' => Str::random(10),
        ]);
        User::create([
            'name' => 'Yifang User',
            'email' => 'user1@yifang.com',
            'role' => 1,
            'username' => 10000,

            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // 123456789
            // 'password' => '$2y$10$7crdZF/aXQJ2bh.QIR/7CO9FhtAz7DrsdIn3w24CTJNxbY6BX/8j2', // 123456789
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin1@yifang.com',
            'username' => 20000,

            'role' => 2,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // 123456789
            // 'password' => '$2y$10$7crdZF/aXQJ2bh.QIR/7CO9FhtAz7DrsdIn3w24CTJNxbY6BX/8j2', // 123456789
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Senior Admin',
            'email' => 'senioradmin1@yifang.com',
            'username' => 30000,

            'role' => 3,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // 123456789
            // 'password' => '$2y$10$7crdZF/aXQJ2bh.QIR/7CO9FhtAz7DrsdIn3w24CTJNxbY6BX/8j2', // 123456789
            'remember_token' => Str::random(10),
        ]);
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin1@yifang.com',
            'username' => 40000,

            'role' => 4,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // 123456789
            // 'password' => '$2y$10$7crdZF/aXQJ2bh.QIR/7CO9FhtAz7DrsdIn3w24CTJNxbY6BX/8j2', // 123456789
            'remember_token' => Str::random(10),
        ]);

        // Branch::create([
        //     'branch' => 'ASB',
        // ]);
        // Branch::create([
        //     'branch' => 'DPA',
        // ]);
        // Branch::create([
        //     'branch' => 'YCME',
        // ]);
        // Branch::create([
        //     'branch' => 'YIG',
        // ]);
        // Branch::create([
        //     'branch' => 'YSM',
        // ]);
    }
}
