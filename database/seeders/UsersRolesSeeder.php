<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_roles')->insert([
            'name' => 'Owner',
        ]);

        DB::table('users_roles')->insert([
            'name' => 'Admin',
        ]);

        DB::table('users_roles')->insert([
            'name' => 'Agent',
        ]);
    }
}
