<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Anhskohbo\NoCaptcha\NoCaptcha;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        // Instancia con Site Key y Secret Key
        $captcha = new NoCaptcha(
            env('NOCAPTCHA_SITEKEY'),
            env('NOCAPTCHA_SECRET')
        );

        // Verificar el token recibido
        $response = $captcha->verifyResponse($request->input('g-recaptcha-response'));

        dd($response); // 👈 imprime la respuesta cruda de Google
        // Validar datos + reCAPTCHA
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'dni' => 'required|digits:8',
            'phone' => 'required|digits:9',
            'password' => 'required|confirmed|min:8',
            'g-recaptcha-response' => 'required|captcha', // validación reCAPTCHA
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear admin
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'dni' => $request->dni,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'Admin registrado correctamente',
            'admin' => $admin
        ]);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'password' => 'sometimes|string|min:6',
            'dni' => 'sometimes|string|max:20',
            'phone' => 'sometimes|string|max:20',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $admin->update($validated);

        return response()->json([
            'message' => 'Admin actualizado correctamente',
            'admin' => $admin
        ]);
    }
}
