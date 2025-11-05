<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100'
        ]);

        $service = Service::create($request->only(['name', 'description', 'category']));

        return response()->json([
            'message' => 'Servicio creado correctamente',
            'service' => $service
        ], 201);
    }
    public function index()
    {
        return response()->json(Service::all());
    }



}
