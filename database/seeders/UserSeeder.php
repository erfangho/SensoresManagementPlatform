<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'role_id' => 3,
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'phone' => '09366223096',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'role_id' => random_int(1, 2),
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'phone' => '09212773435',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'role_id' => random_int(1, 2),
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'phone' => '09911462414',
            'password' => Hash::make('password'),
        ]);
    }
}
