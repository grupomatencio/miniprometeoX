<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zone;
use App\Models\Delegation;

class ZonasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Benidorm',
            'Alicante',
            'Javea',
            'Denia',
            'Taller',
        ])->each(function ($zone) {
            Zone::factory()->create([
                'name' => $zone,
                'delegation_id' => Delegation::where('name', 'Benidorm')->first()->id, // Establecer el delegation_id como 2
            ]);
        });

        /*$delegations = Delegation::all();

        // Iterar sobre cada delegación
        foreach ($delegations as $delegation) {
            // Crear tres zonas para cada delegación
            for ($i = 1; $i <= 3; $i++) {
                // Crear una nueva zona
                $zone = new Zone();
                $zone->name =  $delegation->name;
                // Asociar la zona con la delegación
                $zone->delegation_id = $delegation->id;
                // Guardar la zona en la base de datos
                $zone->save();
            }*/
        }

}
