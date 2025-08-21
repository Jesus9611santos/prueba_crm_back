<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;


class PlaceController extends Controller
{
    // Obtener todos los places
    public function index()
    {
        $places = Place::all();
        return response()->json($places);
    }

    // Obtener un place por ID
    public function show($id)
    {
        $place = Place::find($id);

        if (!$place) {
            return response()->json(['message' => 'Place no encontrado'], 404);
        }

        return response()->json($place);
    }
}
