<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name'=>'Everyone',
            'email'=>'all@gmail.com',
            'password'=>Hash::make('password'),
            'contact_no' => '1234567890',
            'profile_pic' => '1_profile_pic.png',
        ]);
        User::create([
            'name'=>'Pinak',
            'email'=>'pinak@gmail.com',
            'password'=>Hash::make('password'),
            'contact_no' => '1234567890',
            'profile_pic' => '2_profile_pic.png',
        ]);
        User::create([
            'name'=>'Ajay',
            'email'=>'ajay@gmail.com',
            'password'=>Hash::make('password'),
            'contact_no' => '1234567890',
            'profile_pic' => '3_profile_pic.png',
        ]);
        User::create([
            'name'=>'Chirag',
            'email'=>'chirag@gmail.com',
            'password'=>Hash::make('password'),
            'contact_no' => '1234567890',
            'profile_pic' => '4_profile_pic.png',
        ]);

        Message::factory(50)->create();
    }
}
