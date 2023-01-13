<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use App\Models\Slide;
use App\Models\SlideStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SlideController extends Controller
{
    public function index($presentation)
    {
        return response()->json([
            'data' => Slide::where('presentation_id', $presentation)->with(['style', 'detail', 'relations'])->get()
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

    public function update(Request $r, $presentation, $slide)
    {
        $validator = Validator::make($r->only(['title', 'description', 'relations']), [
            'title' => ['required', 'min:5'],
            'description' => ['required', 'min:10'],
            'relations' => ['array'],
            'relations.*.id' => ['required', 'exists:relations,id'],
            'relations.*.slide_part_id' => ['required', 'exists:relations,slide_part_id,' . $presentation . ',id'],
            'relations.*.title' => ['required', 'min:3'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            Slide::find($slide)
                ->fill($r->only(['title', 'description']))
                ->save();

            foreach ($r->relations as $relation) {
                $findRelation = Relation::find($relation->id);
                if ($findRelation->slide_part1_id === $relation->slide_part_id) {
                    $findRelation->title1 = $relation->title;
                } else {
                    $findRelation->title2 = $relation->title;
                }
                $findRelation->save();
            }

            return response()->json(['message' => 'Update Content Success']);
        }
    }

    public function updatePosition(Request $r, $presentation, $slide)
    {
        $data = $r->only(['top', 'left']);
        $validator = Validator::make($data, [
            'top' => ['required', 'integer'],
            'left' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            Slide::find($slide)
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Position Success']);
        }
    }

    public function updateStyle(Request $r, $presentation, $slide)
    {
        $data = $r->only([
            'top', 'left', 'width', 'height',
            'border_color', 'background_color', 'part_color', 'part_background_color',
            'part_used_color', 'part_used_background_color'
        ]);
        $validator = Validator::make($data, [
            'top' => ['required', 'integer'],
            'left' => ['required', 'integer'],
            'width' => ['required', 'integer', 'min:50', 'max:300'],
            'height' => ['required', 'integer', 'min:50', 'max:300'],
            'border_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'part_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'part_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'part_used_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'part_used_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            SlideStyle::where('slide_id', $slide)
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Style Success']);
        }
    }

    public function updateZIndex(Request $r, $presentation, $slide)
    {
        $data = $r->only(['z_index']);
        $validator = Validator::make($data, [
            'z_index' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            SlideStyle::where('slide_id', $slide)
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Z-Index Success']);
        }
    }
}
