<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class AdminUniversityController extends Controller
{
    public function index()
    {
        return response()->json(University::all());
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $university = University::create([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'logo_url' => $data['logoUrl'] ?? null,
            'email_domain' => $data['emailDomain'] ?? $data['email_domain'] ?? null,
        ]);

        return response()->json($university, 201);
    }

    public function update(int $id, Request $request)
    {
        $university = University::findOrFail($id);
        $data = $request->all();

        $university->name = $data['name'] ?? $university->name;
        $university->description = $data['description'] ?? $university->description;
        $university->logo_url = $data['logoUrl'] ?? $university->logo_url;
        $university->save();

        return response()->json($university);
    }

    public function destroy(int $id)
    {
        University::destroy($id);

        return response()->json(['message' => 'University deleted']);
    }
}
