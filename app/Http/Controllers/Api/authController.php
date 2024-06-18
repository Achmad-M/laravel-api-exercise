<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\role;
use App\Models\User; // Adjust the path according to your application structure
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function registerUser(Request $request)
    {
        $datauser = new User();
        $rules = [
            'name' => 'required',
            'email' => 'required | email | unique:users,email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Proses validasi gagal',
                    'data' => $validator->errors(),
                ],
                401,
            );
        }

        $datauser->name = $request->name;
        $datauser->email = $request->email;
        $datauser->password = Hash::make($request->password);
        $datauser->save();

        return response()->json(
            [
                'status' => true,
                'message' => 'Berhasil memasukkan data baru',
            ],
            200,
        );
    }

    public function loginUser(Request $request)
    {
        $rules = [
            'email' => 'required | email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses login gagal',
                'data' => $validator->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email dan password yang dimasukkan tidak sesuai'
            ], 401);
        }

        $datauser = User::where('email', $request->email)->first();
        $role = role::join("user_role", "user_role.role_id", "=", "roles.id")
            ->join("users", "users.id", "=", "user_role.user_id")
            ->where('user_id', $datauser->id)
            ->pluck('roles.role_name')->toArray();

        if (empty($role)) {
            $role = ["*"];
        }
        return response()->json([
            'status' => true,
            'message' => 'Berhasil proses login',
            'token' => $datauser->createToken('api-buku', $role)->plainTextToken
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
