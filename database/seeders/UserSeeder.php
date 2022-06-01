<?php

namespace Database\Seeders;

use App\Enums\UserLevel;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@mirit.pl',
            'password' => Hash::make('12345678'),
            'level' => UserLevel::ADMIN,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Klient',
            'email' => 'admin2@mirit.pl',
            'password' => Hash::make('12345678'),
            'level' => UserLevel::CLIENT,
            'client_id' => Client::first()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Klient2',
            'email' => 'admin4@mirit.pl',
            'password' => Hash::make('12345678'),
            'level' => UserLevel::CLIENT,
            'client_id' => Client::first()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Pracownik',
            'email' => 'admin3@mirit.pl',
            'password' => Hash::make('12345678'),
            'level' => UserLevel::WORKER,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
