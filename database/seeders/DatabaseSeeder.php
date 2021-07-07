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
            'name'=>'Pinak',
            'email'=>'pinak@gmail.com',
            'password'=>Hash::make('password')
        ]);
        User::create([
            'name'=>'Ajay',
            'email'=>'ajay@gmail.com',
            'password'=>Hash::make('password')
        ]);
        User::create([
            'name'=>'Chirag',
            'email'=>'chirag@gmail.com',
            'password'=>Hash::make('password')
        ]);

        Message::factory(10)->create();
    }
}
