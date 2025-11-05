<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        return response()->json(Provider::all());
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => 'required|string|max:11|unique:providers,ruc',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);

        $provider = Provider::create($request->only([
            'name', 'ruc', 'address', 'phone', 'email'
        ]));

        return response()->json([
            'message' => 'Proveedor creado correctamente',
            'provider' => $provider
        ], 201);
    }

}
