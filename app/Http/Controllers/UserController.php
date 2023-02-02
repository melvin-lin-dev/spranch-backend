<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser()
    {
        $user = Auth::user();

        return response()->json(['data' => $user]);
    }
}
