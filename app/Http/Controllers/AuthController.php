<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->checkUserAuth();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'errorCode' => 422
            ]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);
        $token = $user->createToken($user->name . '-token')->plainTextToken;
        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'new user registeresd',
            'httpCode' => 200,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {

        $this->checkUserAuth($request->email);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken($user->name . '-token')->plainTextToken;
            return response()->json([
                'status' => true,
                'token' => $token,
                'message' => 'Login successful',
                'httpCode' => 200,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication failed',
                'httpCode' => 401
            ]);
        }
    }

    public function profile()
    {


        // $this->checkUserAuth();
        $data = [
            'name' => Auth::user()->name,
            'email' => Auth::user()->email
        ];
        // return response()->json([
        //     'message'=>'test'
        // ]);
        return $this->responseMessage(true, 'User profile info', $data, 200);
    }

    public function logout(Request $request)
    {
        $this->checkUserAuth();
        $user = Auth::user();
        if ($user->tokens) {
            $user->tokens()->delete();
        }
        $this->responseMessage(true, 'User logged out', null, 200);
    }
}
