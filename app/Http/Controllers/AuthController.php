<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Importación necesaria para el método attempt()
use Illuminate\Support\Facades\Log; // Importación añadida
use App\Models\Admin;

class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'dni' => 'required|string|size:8',
            'phone' => 'required|string|size:9',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required|string'
        ]);

        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $recaptchaResponse = $validated['g-recaptcha-response'];

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip()
        ]);

        $responseData = $response->json();

        if (!$responseData['success']) {
            Log::error('reCAPTCHA failed', [
                'errors' => $responseData['error-codes'] ?? [],
                'request' => $request->all()
            ]);

            return response()->json([
                'message' => 'Error en verificación de seguridad',
                'errors' => [
                    'g-recaptcha-response' => [
                        'La verificación reCAPTCHA falló. Código: ' . ($responseData['error-codes'][0] ?? 'unknown')
                    ]
                ]
            ], 422);
        }

        // Crear admin
        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'dni' => $validated['dni'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Administrador registrado exitosamente',
            'admin' => $admin
        ], 201);
    }


    /**
     * Inicia sesión con el usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico no es válido',
            'password.required' => 'La contraseña es obligatoria'
        ]);


        // Buscar al admin manualmente
        $admin = Admin::where('email', $credentials['email'])->first();

        // Verificar existencia y contraseña
        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Elimina tokens existentes para evitar múltiples sesiones
        $admin->tokens()->delete();

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $admin,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Cierra la sesión del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }
}
