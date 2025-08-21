<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fraccionamiento' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'telefono' => ['required', 'regex:/^\d{10}$/'], // exactamente 10 números
            'lote' => ['required', 'regex:/^\d+$/'], // solo números
            'manzana' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ]
        ], [
            // Mensajes personalizados de "required"
            'fraccionamiento.required' => 'El campo Fraccionamiento es obligatorio.',
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'telefono.required' => 'El campo Teléfono es obligatorio.',
            'lote.required' => 'El campo Lote es obligatorio.',
            'manzana.required' => 'El campo Manzana es obligatorio.',
            'email.required' => 'El campo Email es obligatorio.',
            'password.required' => 'El campo Contraseña es obligatorio.',

            // Otros mensajes
            'telefono.regex' => 'El teléfono debe tener exactamente 10 dígitos numéricos.',
            'lote.regex' => 'El lote solo debe contener números.',
            'email.email' => 'El email no es válido.',
            'email.unique' => 'El email ya está registrado.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        $user = User::create([
            'place_id' => $request->fraccionamiento,
            'name' => $request->nombre,
            'phone' => $request->telefono,
            'lot' => $request->lote,
            'block' => $request->manzana,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    public function recovery(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'El email no está registrado'], 404);
        }

        // Generar contraseña nueva que cumpla reglas
        $newPassword = $this->generateStrongPassword();

        // Guardar la contraseña hasheada
        $user->password = Hash::make($newPassword);
        $user->save();

        // Enviar correo con la nueva contraseña
        Mail::raw("Tu nueva contraseña es: $newPassword", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Recuperación de contraseña');
        });

        return response()->json(['message' => 'Nueva contraseña enviada al correo']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Crear token para API
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada en todos los dispositivos'
        ]);
    }

    public function show(){
        return response()->json(['data' => Auth::user()->load('place')]);
    }

    // Función para generar contraseña fuerte
    private function generateStrongPassword($length = 10) {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()-_=+';

        // Asegurar que tenga al menos un caracter de cada tipo
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        // Rellenar con caracteres aleatorios hasta el largo deseado
        $all = $uppercase . $lowercase . $numbers . $special;
        for ($i = 4; $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        // Mezclar caracteres para no tener siempre el mismo orden
        return str_shuffle($password);
    }
}
