<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required',
            'password' =>'required'
        ]);

        if ( !Auth::attempt($credentials)){
            throw ValidationException::withMessages([
                'email' =>[
                    _('auth.failed')
                ]
                ]);
        }
        return $request->user();
    }

    public function logout(){
        return Auth::logout();
    }

    public function getUser(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // El usuario está autenticado, obtener sus datos
            $user = $request->user();

            // Devolver los datos del usuario
            return response()->json([
                'user' => $user
            ]);
        } else {
            // El usuario no está autenticado, devolver un mensaje de error
            return response()->json([
                'error' => 'Usuario no autenticado'
            ], 401); // Código de estado 401: No autorizado
        }
    }
}
