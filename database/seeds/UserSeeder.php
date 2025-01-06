<?php

use Illuminate\Database\Seeder;

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
            'name' => 'Testing User',
            'role_id' => '1',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'isactive' => '1', // password
            'remember_token' => Str::random(10),
            'password' => Hash::make('password'),
        ]);
    }
}
