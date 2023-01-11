<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\Slide;
use App\Models\SlideStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $r, $presentation)
    {
        $data = $r->only(['element', 'top', 'left']);
        $validator = Validator::make($data, [
            'element' => ['required', 'in:circle,square'],
            'top' => ['required', 'integer'],
            'left' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $slides = Slide::where(['presentation_id' => $presentation])->get()->sortByDesc('style.z_index', true)->values();

            $slide = Slide::create([
                'element' => $data['element'],
                'presentation_id' => $presentation,
                'is_first' => $slides->count() === 0,
            ]);

            SlideStyle::create([
                'slide_id' => $slide->id,
                'top' => $data['top'],
                'left' => $data['left'],
                'z_index' => $slides[0]->style->z_index + 1
            ]);

            return response()->json(['data' => $slide]);
        }
    }
}
