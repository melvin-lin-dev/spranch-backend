<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        // TODO: temporary
        $presentation = Presentation::first();

        return response()->json([
            'data' => Slide::where('presentation_id', $presentation->id)->with(['style', 'detail', 'relations'])->get()
        ]);
    }
}
