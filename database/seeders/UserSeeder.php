<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name' => "miniprometeo",
            'password' => bcrypt("qwert123"),
        ]);
        User::create([
            'name' => "tecnico",
            'password' => bcrypt("qwert123"),
        ]);
        User::create([
            'name' => "caja",
            'password' => bcrypt("qwert123"),
        ]);
        User::create([
            'name' => "ccm",
            'password' => Crypt::encryptString("ccm10"),
        ]);
        User::create([
            'name' => "admin",
            'password' => Crypt::encryptString("master"),
        ]);

    }
}
