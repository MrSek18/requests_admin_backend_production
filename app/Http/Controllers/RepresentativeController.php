<?php

namespace App\Http\Controllers;

use App\Models\Representative;
use App\Models\CompanyRepresentative;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function index()
    {
        return response()->json(Representative::all());
    }

    public function byCompany($company_id)
    {
        $repIds = CompanyRepresentative::where('company_id', $company_id)->pluck('representative_id');
        $reps = Representative::whereIn('id', $repIds)->get();
        return response()->json($reps);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:representatives,dni',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => 'nullable|string|max:255'
        ]);

        $representative = Representative::create($validated);

        return response()->json([
            'message' => 'Representante creado correctamente',
            'representative' => $representative
        ], 201);
    }

}

