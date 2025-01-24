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
            'name' => "Miniprometeo",
            'email' => 'muchamiel@magarin.es',
            'password' => Crypt::encryptString("Mini1234"),
        ]);
        User::create([
            'name' => "tecnico",
            'password' => bcrypt("Tecnico1234"),
        ]);
        User::create([
            'name' => "caja",
            'password' => bcrypt("Caja1234"),
        ]);
        User::create([
            'name' => "prometeo",
            'email' => 'prometeo@magarin.es',
            'password' => Crypt::encryptString("Admin1234"),
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
