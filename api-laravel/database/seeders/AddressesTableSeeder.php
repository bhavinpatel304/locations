<?php

// database/seeders/AddressesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesTableSeeder extends Seeder {
    public function run(): void {
        DB::table('addresses')->insert([
            'id' => 1,
            'name' => 'Jon Doe',
            'address' => '500 Eau Claire Ave SW',
            'address2' => '',
            'postal_code' => 'T2P 3R8',
            'city' => 'Calgary',
            'province' => 'AB',
            'id_country' => 1,
            'phone' => '6045551234',
            'deleted' => 0,
        ]);
    }
}

