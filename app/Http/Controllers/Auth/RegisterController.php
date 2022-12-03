<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the form register
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
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
            'data' => ['user' => $user]
        ], 201);
    }
}
