<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkTimeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('work_time_types')->insert([
            'name' => 'Montaż',
            'colour' => '#1fee1f',
            'order' => '1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('work_time_types')->insert([
            'name' => 'Zdjęcia',
            'colour' => '#d2ce13',
            'order' => '2',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('work_time_types')->insert([
            'name' => 'Scenariusz',
            'colour' => '#eb1d1d',
            'order' => '3',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
