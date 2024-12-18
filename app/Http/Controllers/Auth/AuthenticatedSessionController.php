<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        
        if (Auth::user()!= null) {
            return response()->json([
                'message' => 'User already logged in',
                'httpCode' => 200
            ]);
        }
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'message' => 'Login successful',
            'hhtCode' => 200,
        ]);

        return response()->json([
            'message' => Auth::user(),
            'hhtCode' => 200
        ]);

        // return response()->noContent();

    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        

        return response()->json([
            'message' => 'User Logged out',
            'hhtCode' => 200
        ]);
    }
}
