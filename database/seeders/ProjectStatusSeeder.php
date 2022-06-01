<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_statuses')->insert([
            'name' => 'Rozmowy z klientem',
            'colour' => '#1fee1f',
            'order' => '1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('project_statuses')->insert([
            'name' => 'Pre produkcja',
            'colour' => '#d2ce13',
            'order' => '2',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('project_statuses')->insert([
            'name' => 'Produkcja',
            'colour' => '#eb1d1d',
            'order' => '3',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
