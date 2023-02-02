<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\PresentationStyle;
use App\Models\Relation;
use App\Models\Slide;
use App\Models\SlidePart;
use App\Models\SlideStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $data = $r->only(['id', 'style_id', 'element', 'top', 'left']);
        $validator = Validator::make($data, [
            'id' => ['required', 'uuid', 'unique:slides'],
            'style_id' => ['required', 'uuid', 'unique:slide_styles,id'],
            'element' => ['required', 'in:circle,square'],
            'top' => ['required', 'integer'],
            'left' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $slides = Slide::where(['presentation_id' => $presentation])->get()->sortByDesc('style.z_index', true)->values();

            $slide = Slide::create([
                'id' => $data['id'],
                'element' => $data['element'],
                'presentation_id' => $presentation,
                'is_first' => $slides->count() === 0,
            ]);

            SlideStyle::create([
                'id' => $data['style_id'],
                'slide_id' => $data['id'],
                'top' => $data['top'],
                'left' => $data['left'],
                'z_index' => $slides->count() ? $slides[0]->style->z_index + 1 : 1
            ]);

            return response()->json(['data' => $slide]);
        }
    }

    public function createDetail(Request $r, $presentation, $slide)
    {
        $data = $r->only(['id', 'style_id', 'is_main', 'is_favorite']);
        $validator = Validator::make($data, [
            'id' => ['required', 'uuid', 'unique:presentations'],
            'style_id' => ['required', 'uuid', 'unique:presentation_styles,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            Presentation::create([
                'id' => $data['id'],
                'user_id' => Auth::user()['id'],
                'is_main' => false,
                'is_favorite' => false
            ]);

            PresentationStyle::create([
                'id' => $data['style_id'],
                'presentation_id' => $data['id']
            ]);

            Slide::find($slide)
                ->fill(['detail_id' => $data['id']])
                ->save();

            return response()->json(['message' => 'Create Detail Success']);
        }
    }

    public function update(Request $r, $presentation, $slide)
    {
        $validator = Validator::make($r->only(['title', 'description', 'relations']), [
//            'title' => ['required', 'min:5'],
//            'description' => ['required', 'min:10'],
            'relations' => ['array'],
            'relations.*.id' => ['required', 'uuid', 'exists:relations,id'],
            'relations.*.slide_part_id' => ['required', 'uuid', 'exists:relations,slide_part_id,' . $presentation . ',id'],
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
            SlideStyle::where('slide_id', $slide)->first()
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
            SlideStyle::where('slide_id', $slide)->first()
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Style Success']);
        }
    }

    public function updateZIndex(Request $r, $presentation, $slide)
    {
        $data = $r->only(['type']);
        $validator = Validator::make($data, [
//            'z_index' => ['required', 'integer'],
            'type' => ['required', 'in:to_front,to_back,bring_forward,send_backward'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $slides = Slide::where('presentation_id', $presentation)->get()->sortBy('style.z_index')->values();

            $style = SlideStyle::where('slide_id', $slide)->first();

            switch ($r['type']) {
                case 'to_front':
                    $style->fill(['z_index' => $slides->last()->style->z_index + 1])->save();
                    break;
                case 'to_back':
                    $style->fill(['z_index' => $slides->first()->style->z_index - 1])->save();
                    break;
                case 'bring_forward':
                    $upperSlideIndex = $slides->search(function ($curSlide) use ($slide) {
                            return $curSlide->id === $slide;
                        }) + 1;

                    if ($upperSlideIndex < $slides->count()) {
                        $upperSlide = $slides[$upperSlideIndex];
                        $tempZIndex = $style->z_index;
                        $style->fill(['z_index' => $upperSlide->style->z_index])->save();
                        $upperSlide->style->fill(['z_index' => $tempZIndex])->save();
                    }
                    break;
                case 'send_backward':
                    $lowerSlideIndex = $slides->search(function ($curSlide) use ($slide) {
                            return $curSlide->id === $slide;
                        }) - 1;

                    if ($lowerSlideIndex >= 0) {
                        $lowerSlide = $slides[$lowerSlideIndex];
                        $tempZIndex = $style->z_index;
                        $style->fill(['z_index' => $lowerSlide->style->z_index])->save();
                        $lowerSlide->style->fill(['z_index' => $tempZIndex])->save();
                    }
                    break;
            }

            return response()->json(['message' => 'Update Z-Index Success']);
        }
    }

    public function destroy($presentation, $slide)
    {
        $slide = Slide::with('relations')->find($slide);

        foreach ($slide->relations as $slidePart) {
            $relations = Relation::where('slide_part1_id', $slidePart->id)->orWhere('slide_part2_id', $slidePart->id)->get();

            foreach ($relations as $relation) {
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
            }
        }

        $slide->delete();

        return response()->json(['message' => 'Delete Slide Success!']);
    }

    public function deleteDetail($presentation, $slide)
    {
        Slide::find($slide)->fill(['detail_id' => null])->save();

//        Presentation::find($presentation)->touch();

        return response()->json(['message' => 'Delete Slide Success!']);
    }
}
