<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("user_roles")->insert([
            "id" => "1",
            "name" => "admin"
        ]);

        DB::table("user_roles")->insert([
            "id" => "2",
            "name" => "user"
        ]);
    }
}
