<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use App\Models\RelationStyle;
use App\Models\SlidePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelationController extends Controller
{
    public function index($presentation)
    {
        return response()->json([
            'data' => Relation::where('presentation_id', $presentation)->get()
        ]);
    }

    public function store(Request $r, $presentation)
    {
        $data = $r->only(['slide1_id', 'slide2_id', 'number1', 'number2']);
        $validator = Validator::make($data, [
            'slide1_id' => ['required', 'exists:slides,id'],
            'slide2_id' => ['required', 'exists:slides,id'],
            'number1' => ['required', 'integer', 'exists:slides,id'],
            'number2' => ['required', 'integer', 'exists:slides,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $slidePart1 = SlidePart::create([
                'slide_id' => $data['slide1_id'],
                'number' => $data['number1']
            ]);

            $slidePart2 = SlidePart::create([
                'slide_id' => $data['slide2_id'],
                'number' => $data['number2']
            ]);

            $relation = Relation::create([
                'slide_part1_id' => $slidePart1->id,
                'slide_part2_id' => $slidePart2->id
            ]);

            RelationStyle::create([
                'relation_id' => $relation->id,
            ]);

            return response()->json(['data' => $relation]);
        }
    }

    public function updateStyle(Request $r, $presentation, $relation)
    {
        $data = $r->only(['background_color']);
        $validator = Validator::make($data, [
            'background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            RelationStyle::where('relation_id', $relation)
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Style Success']);
        }
    }
}
