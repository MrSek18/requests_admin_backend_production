<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
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
