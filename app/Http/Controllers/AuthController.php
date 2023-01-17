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
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'min:8', 'same:password']
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
        $data = $r->only(['email', 'password']);
        $validator = Validator::make($data, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            if (Auth::attempt(['email' => $r->email, 'password' => $r->password])) {
                $user = Auth::user();

                $success['token'] = $user->createToken($user['email'])->plainTextToken;

                return response()->json(['data' => $success]);
            } else {
                return response()->json(['message' => 'Incorrect Email Or Password!'], 401);
            }
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout Success!']);
    }

    public function verifyMail()
    {
        $user = Auth::user();
        Mail::to($user['email'])->send(new VerifyMail());
    }
}
