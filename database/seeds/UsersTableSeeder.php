<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            "username" => "admin",
            "password" => Hash::make("admin123"),
            "user_role_id" => 1
        ]);
    }
}
