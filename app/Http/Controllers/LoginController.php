<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['registration', 'login']]);
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'   => 'required|email',
                'password'   => 'required',
            ],
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Form Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        } else {
            $credentials = $request->only('email', 'password');
            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            } else {
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Credential Error',
                    'errors' => array('Invalid Credential!'),
                ], 422);
            }
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function registration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        return $this->create($data);
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }
}