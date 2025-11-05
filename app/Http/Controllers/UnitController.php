<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = \App\Models\Unit::select('id', 'name', 'abbreviation', 'created_at')->get();

        return response()->json($units);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50'
        ]);

        $unit = Unit::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation
        ]);

        return response()->json([
            'message' => 'Unidad creada correctamente',
            'unit' => $unit
        ], 201);
    }

}

