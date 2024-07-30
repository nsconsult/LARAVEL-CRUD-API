<?php

namespace App\Http\Controllers;

use App\Models\TestUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class TestUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TestUser::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:test_users',
            'password' => 'required|string|min:8',
        ]);

        $user = TestUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return TestUser::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = TestUser::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:test_users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $user->update($request->only(['name', 'email', 'password']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        TestUser::destroy($id);

        return response()->json(null, 204);
    }
}
