<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $names = [
            "AMPLIACIÓN TERRA (GRAND PRESERVE)",
            "ARBOLE (BIO PRESERVE)",
            "ARBOLE II (BIO PRESERVE)",
            "BIOGRAND (GRAND OUTDOORS)",
            "GRAND JURIQUILLA",
            "JURIQUILLA CAMPESTRE",
            "PRESERVE AQUA",
            "PRESERVE SUR",
            "PRIVADA VENTO",
            "REAL DE JURIQUILLA",
            "RESERVA BIO",
            "TERRA (GRAND PRESERVE)",
            "TERRA II (GRAND PRESERVE)",
            "TERRA III (GRAND PRESERVE)",
            "VENTO",
            "VENTO II",
        ];

        foreach ($names as $name) {
            Place::create(['name' => $name]);
        }
    }
}
