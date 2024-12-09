<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['name' => 'Quezon City', 'description' => 'A highly urbanized city', 'capacity' => 100, 'location' => 'Quezon City'],
            ['name' => 'Manila', 'description' => 'The capital city of the Philippines', 'capacity' => 200, 'location' => 'Manila'],
            ['name' => 'Makati', 'description' => 'The financial center of the Philippines', 'capacity' => 150, 'location' => 'Makati'],
            ['name' => 'Taguig', 'description' => 'Home to Bonifacio Global City', 'capacity' => 120, 'location' => 'Taguig'],
            ['name' => 'Pasig', 'description' => 'Known for its business district', 'capacity' => 130, 'location' => 'Pasig'],
            ['name' => 'Mandaluyong', 'description' => 'The Tiger City of the Philippines', 'capacity' => 110, 'location' => 'Mandaluyong'],
            ['name' => 'Parañaque', 'description' => 'Known for its entertainment city', 'capacity' => 140, 'location' => 'Parañaque'],
            ['name' => 'Las Piñas', 'description' => 'Famous for its bamboo organ', 'capacity' => 90, 'location' => 'Las Piñas'],
            ['name' => 'Muntinlupa', 'description' => 'Home to Alabang', 'capacity' => 80, 'location' => 'Muntinlupa'],
            ['name' => 'Caloocan', 'description' => 'A major residential area', 'capacity' => 160, 'location' => 'Caloocan'],
        ];

        DB::table('rooms')->insert($rooms);
    }
}
