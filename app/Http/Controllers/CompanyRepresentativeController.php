<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyRepresentative;

class CompanyRepresentativeController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'representative_id' => 'required|exists:representatives,id',
            'position' => 'required|string|max:255'
        ]);

        $link = CompanyRepresentative::create($request->only([
            'company_id', 'representative_id', 'position'
        ]));

        return response()->json([
            'message' => 'Vínculo registrado correctamente',
            'company_representative' => $link
        ], 201);
    }
}
