<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
        $this->middleware('jwt.xauth', ['except' => ['login', 'register', 'refresh']]);
        $this->middleware('jwt.verify', ['only' => ['refresh']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'between:2,100'],
                'email' => ['required', 'string', 'email', 'unique:users,email'],
                'username' => ['required', 'regex:/^[a-zA-Z0-9]([._-](?![._-])|[a-zA-Z0-9]){3,18}[a-zA-Z0-9]$/', 'unique:users,username'],
                'password' => ['required', 'regex:/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*/', 'confirmed']
            ],
            [
                'username.regex' => 'Username must be at least 5 characters long, '
                    . 'consists of alphanumeric characters (both lowercase or uppercase), '
                    . 'allowed of the dot (.), underscore (_), and hyphen (-) '
                    . 'does not appear consecutively, e.g., java..regex',
                'password.regex' => 'Passwords must be at least 8 characters long, '
                    . 'containing at least 1 lowercase letter, 1 uppercase letter, '
                    . '1 number, and a special character (non-word characters)'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully registered',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $identity = $request->identity;
        if (is_numeric($identity)) {
            $field = 'phone_number';
        } elseif (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }
        $request->merge([$field => $identity]);

        $validator = Validator::make(
            $request->all(),
            [
                $field => ['required', 'string'],
                'password' => ['required', 'string', 'min:8']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        if (!$token = auth('api')->claims(['xtype' => 'auth'])->attempt($validator->validated())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized user login attempt'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Current authenticated user',
            'data' => auth('api')->user()
        ], 200);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function refresh()
    {
        $access_token = auth('api')->claims(['xtype' => 'auth'])->refresh(true, true);
        auth('api')->setToken($access_token);
        return $this->respondWithToken($access_token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully authenticated',
            'data' => [
                'token_type' => 'bearer',
                'access_token' => $token,
                'access_expires_in' => auth('api')->factory()->getTTL() * 60,
                'refresh_token' => auth('api')
                    ->claims([
                        'xtype' => 'refresh',
                        'xpair' => auth('api')->payload()->get('jti')
                    ])
                    ->setTTL(auth('api')->factory()->getTTL() * 3)
                    ->tokenById(auth('api')->user()->id),
                'refresh_expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
