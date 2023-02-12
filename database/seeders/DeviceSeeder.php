<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->insert([
            'name' => '14011223',
            'api_key' => Str::random(12),
            'phone_number' => '09366223096',
            'sub_zone_id' => 2,
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);

        DB::table('devices')->insert([
            'name' => '14011224',
            'api_key' => Str::random(12),
            'phone_number' => '09127757469',
            'sub_zone_id' => 2,
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);

        DB::table('devices')->insert([
            'name' => 'A2061',
            'api_key' => Str::random(12),
            'phone_number' => '09193781058',
            'sub_zone_id' => 1,
            'user_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);

        DB::table('devices')->insert([
            'name' => 'A2062',
            'api_key' => Str::random(12),
            'phone_number' => '09212773435',
            'sub_zone_id' => 1,
            'user_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ]);
    }
}
