<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function index()
    {
        $presentations = Presentation::where('is_main', true)->get();

        return response()->json([
            'data' => $presentations
        ]);
    }
    public function getFavoritedPresentations()
    {
        $presentations = Presentation::where(['is_main' => true, 'is_favorite' => true])->get();

        return response()->json([
            'data' => $presentations
        ]);
    }

    public function show($presentation)
    {
        $presentation = Presentation::find($presentation);

        return response()->json([
            'data' => $presentation
        ]);
    }
}
