<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $universityId = $request->query('universityId');
        $role = $request->query('role');

        if ($universityId !== null) {
            $users = User::where('university_id', $universityId)->get();
        } elseif ($role !== null) {
            $users = User::where('role', $role)->get();
        } else {
            $users = User::all();
        }

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $roleValue = $data['role'] ?? 'SUPERVISOR';
        $role = strtoupper((string) $roleValue);
        if ($role !== 'SUPERVISOR' && $role !== 'ADMIN') {
            $role = 'SUPERVISOR';
        }

        $user = User::create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            'role' => $role,
            'university_id' => $data['universityId'] ?? null,
        ]);

        return response()->json($user, 201);
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(int $id, Request $request)
    {
        $user = User::findOrFail($id);
        $updates = $request->all();

        if (array_key_exists('name', $updates)) {
            $user->name = $updates['name'];
        }

        if (array_key_exists('email', $updates)) {
            $user->email = $updates['email'];
        }

        if (array_key_exists('role', $updates)) {
            $user->role = $updates['role'];
        }

        if (array_key_exists('points', $updates)) {
            $user->points = (int) $updates['points'];
        }

        $user->save();

        return response()->json($user);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User deactivated']);
    }
}
