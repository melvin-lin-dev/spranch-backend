<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use Illuminate\Http\Request;

class RelationController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Relation::get()
        ]);
    }
}
