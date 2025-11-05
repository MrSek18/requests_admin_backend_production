<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json(Company::all());
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => 'required|string|max:11|unique:companies,ruc',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'sector' => 'nullable|string|max:100'
        ]);

        $company = Company::create($request->only([
            'name', 'ruc', 'address', 'phone', 'email', 'sector'
        ]));

        return response()->json([
            'message' => 'Compañía creada correctamente',
            'company' => $company
        ], 201);
    }

}
