<?php

use Illuminate\Database\Seeder;

//use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\User::class, 50)->create(
            [
                'name' => str_random(10),
                'email' => str_random(20) . '@gmail.com',
                'password' => bcrypt('secret'),
            ]
        );
//        \App\User::insert([
//            'name' => str_random(10),
//            'email' => str_random(10) . '@gmail.com',
//            'password' => bcrypt('secret'),
//        ]);
    }
}
