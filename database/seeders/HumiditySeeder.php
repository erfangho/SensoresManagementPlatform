<?php

namespace Database\Seeders;

use App\Models\humidity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HumiditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        humidity::factory(20)->create();
    }
}
