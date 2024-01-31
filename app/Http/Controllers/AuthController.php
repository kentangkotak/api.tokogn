<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string'
        // ]);
        // $credentials = $request->only('email', 'password');

        // $token = Auth::attempt($credentials);
        // return($token);
        // if(!$token)
        // {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized',
        //     ], 401);
        // }

        // $user = Auth::user();
        // return response()->json([
        //     'status' => 'success',
        //     'user' => $user,
        //     'authorisation' => [
        //         'token' => $token,
        //         'type' => 'bearer',
        //     ]
        // ]);
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        $temp = User::where('email','=', $request->email)->first();
        if(!$temp)
        {
            return new JsonResponse(['message' => 'Periksa Kembali Username dan password anda'], 409);
        }
        if($temp)
        {
            $pass = Hash::check($request->password, $temp->password);
            if(!$pass)
            {
                return new JsonResponse(['message' => 'Periksa Kembali Username dan password anda'], 409);
            }
        }
        if(!$token = auth()->attempt($validator->validated()))
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = User::find(auth()->user()->id);
        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
