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
        $data = $r->only(['id', 'style_id', 'slide_part1_id', 'slide_part2_id', 'slide1_id', 'slide2_id', 'number1', 'number2']);
        $validator = Validator::make($data, [
            'id' => ['required', 'uuid', 'unique:relations'],
            'style_id' => ['required', 'uuid', 'unique:relation_styles,id'],
            'slide_part1_id' => ['required', 'uuid', 'unique:slide_parts,id'],
            'slide_part2_id' => ['required', 'uuid', 'unique:slide_parts,id'],
            'slide1_id' => ['required', 'exists:slides,id'],
            'slide2_id' => ['required', 'exists:slides,id'],
            'number1' => ['required', 'integer'],
            'number2' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $slidePart1 = SlidePart::find($data['slide_part1_id']);
            if ($slidePart1) {
                SlidePart::create([
                    'id' => $data['slide_part1_id'],
                    'slide_id' => $data['slide1_id'],
                    'number' => $data['number1']
                ]);
            }

            $slidePart2 = SlidePart::find($data['slide_part2_id']);
            if ($slidePart2) {
                SlidePart::create([
                    'id' => $data['slide_part2_id'],
                    'slide_id' => $data['slide2_id'],
                    'number' => $data['number2']
                ]);
            }

            $relation = Relation::create([
                'id' => $data['id'],
                'presentation_id' => $presentation,
                'slide_part1_id' => $data['slide_part1_id'],
                'slide_part2_id' => $data['slide_part2_id']
            ]);

            RelationStyle::create([
                'id' => $data['style_id'],
                'relation_id' => $data['id'],
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
            RelationStyle::where('relation_id', $relation)->first()
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Style Success']);
        }
    }

    public function destroy($presentation, $relation)
    {
        $relation = Relation::find($relation);

        $slidePart1 = SlidePart::find($relation->slide_part1_id);
        $slidePart2 = SlidePart::find($relation->slide_part2_id);

        $slideParts1 = Relation::where('slide_part1_id', $slidePart1->id)->orWhere('slide_part2_id', $slidePart1->id)->get();
        $slideParts2 = Relation::where('slide_part1_id', $slidePart2->id)->orWhere('slide_part2_id', $slidePart2->id)->get();

        $relation->delete();

        if ($slideParts1->count() === 1) {
            $slidePart1->delete();
        }

        if ($slideParts2->count() === 1) {
            $slidePart2->delete();
        }

        return response()->json(['message' => 'Delete Relation Success!']);
    }
}
