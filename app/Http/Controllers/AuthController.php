<?php

namespace App\Http\Controllers;

use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $data = $r->only(['email', 'password', 'password_confirmation']);
        $validator = Validator::make($data, [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
            'password_confirmation' => ['required', 'same:password']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            return response()->json(['message' => 'Register Success!']);
        }
    }

    public function login(Request $r)
    {
        if (Auth::attempt(['email' => $r->email, 'password' => $r->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken($user['email'])->plainTextToken;
            $success['email'] = $user['email'];

            return response()->json(['data' => $success]);
        } else {
            return response()->json(['message' => 'Email/Password Is Invalid!']);
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
    }

    public function verifyMail()
    {
        $user = Auth::user();
        Mail::to($user['email'])->send(new VerifyMail());
    }
}
