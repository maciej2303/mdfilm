<?php

namespace Database\Seeders;

use App\Enums\ClientStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'name' => 'Klient testowy',
            'nip' => '1234567890',
            'address' => 'Warszawa',
            'contact_person' => 'Testowy kontakt',
            'phone_number' => '123456789',
            'additional_informations' => 'Example informations',
            'status' => ClientStatus::ACTIVE,
            'who_add' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('clients')->insert([
            'name' => 'Klient testowy2',
            'nip' => '1234567890',
            'address' => 'Warszawa',
            'contact_person' => 'Testowy kontakt',
            'phone_number' => '123456789',
            'additional_informations' => 'Example informations',
            'status' => ClientStatus::ACTIVE,
            'who_add' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('clients')->insert([
            'name' => 'Klient testowy3',
            'nip' => '1234567890',
            'address' => 'Warszawa',
            'contact_person' => 'Testowy kontakt',
            'phone_number' => '123456789',
            'additional_informations' => 'Example informations',
            'status' => ClientStatus::ACTIVE,
            'who_add' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
