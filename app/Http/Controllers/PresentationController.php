<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function index()
    {
        // TODO: temporary
        $presentation = Presentation::first();

        return response()->json([
            'data' => $presentation
        ]);
    }

    public function show($presentation)
    {
        // TODO: temporary
        $presentation = Presentation::first();

        return response()->json([
            'data' => $presentation
        ]);
    }
}
