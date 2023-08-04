<?php

namespace App\Http\Controllers\Citizen;

use App\Models\CitizenAccounts;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:citizen', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('email', 'password');
            $token = Auth::guard('citizen')->attempt($credentials);

            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::guard('citizen')->user();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }

        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $exception) {
            return response()->json($exception->getMessage());
        }


        $user = CitizenAccounts::create([
            'name' => $request->name,
            'prenume' => $request->prenume,
            'email' => $request->email,
            'telefon' => $request->telefon,
            'password' => Hash::make($request->password),
            'email_verified' => $request->email,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::guard('citizen')->logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::guard('citizen')->user(),
            'authorisation' => [
                'token' => Auth::guard('citizen')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function user()
    {
        return response()->json(Auth::guard('citizen')->user());
    }
}
