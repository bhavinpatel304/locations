<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder {
    public function run(): void {
        DB::table('countries')->insert([
            'id' => 1,
            'name' => 'Canada',
            'alpha2_code' => 'CA',
            'alpha3_code' => 'CAN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

