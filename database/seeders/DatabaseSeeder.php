<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Current;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersRolesSeeder::class,
            UserSeeder::class,
            ZoneSeeder::class,
            SubZoneSeeder::class,
            DeviceSeeder::class,
            TemperatureSeeder::class,
            HumiditySeeder::class,
            CurrentSeeder::class,
        ]);
    }
}
