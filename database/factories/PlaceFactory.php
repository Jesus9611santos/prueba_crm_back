<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    protected $model = \App\Models\Place::class;

    protected static $names = [
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

    public function definition()
    {
        return [
            'name' => self::$names[array_rand(self::$names)],
        ];
    }
}
