<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\PresentationStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PresentationController extends Controller
{
    public function index()
    {
        $presentations = Presentation::where([
            'user_id' => Auth::user()['id'],
            'is_main' => true
        ])->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'data' => $presentations
        ]);
    }

    public function getFavoritedPresentations()
    {
        $presentations = Presentation::where([
            'user_id' => Auth::user()['id'],
            'is_main' => true,
            'is_favorite' => true
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $presentations
        ]);
    }

    public function show($presentation)
    {
        $presentation = Presentation::find($presentation);
        $presentation->touch();

        return response()->json([
            'data' => $presentation
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->only(['id', 'style_id', 'is_main', 'is_favorite']);
        $validator = Validator::make($data, [
            'id' => ['required', 'uuid', 'unique:presentations'],
            'style_id' => ['required', 'uuid', 'unique:presentation_styles,id'],
            'is_main' => ['required', 'boolean'],
            'is_favorite' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
//            $data['user_id'] = Auth::user()['id'];
            $presentation = Presentation::create([
                'id' => $data['id'],
                'user_id' => Auth::user()['id'],
                'is_main' => $data['is_main'],
                'is_favorite' => $data['is_favorite']
            ]);

            PresentationStyle::create([
                'id' => $data['style_id'],
                'presentation_id' => $data['id']
            ]);

            return response()->json(['data' => $presentation]);
        }
    }

    public function update(Request $r, $presentation)
    {
        $data = $r->only(['title', 'description']);
        $validator = Validator::make($data, [
            'title' => ['required', 'min:5'],
            'description' => ['required', 'min:10']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            Presentation::find($presentation)
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Content Success']);
        }
    }

    public function updateImages(Request $r, $presentation)
    {
        $validator = Validator::make($r->only(['logo', 'thumbnail']), [
            'logo' => ['mimes:jpeg,jpg,png', 'max:512000'],
            'thumbnail' => ['mimes:jpeg,jpg,png', 'max:512000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $presentation = Presentation::find($presentation);

            if ($r->logo) {
                $presentation->logo = upload_file($r, 'logo', get_design_presentation_logo_path(true));
            }

            if ($r->thumbnail) {
                $presentation->thumbnail = upload_file($r, 'thumbnail', get_design_presentation_thumbnail_path(true));
            }

            $presentation->save();

            return response()->json(['message' => 'Update Images Success']);
        }
    }

    public function updateFavorite(Request $r, $presentation)
    {
        $data = $r->only(['is_favorite']);
        $validator = Validator::make($data, [
            'is_favorite' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            Presentation::find($presentation)
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Manage Favorite Success']);
        }
    }

    public function updateStyle(Request $r, $presentation)
    {
        $data = $r->only([
            'background_color', 'selected_element_color', 'first_slide_border_color', 'first_slide_background_color',
            'first_slide_part_color', 'first_slide_part_background_color', 'first_slide_part_used_color', 'first_slide_part_used_background_color'
        ]);
        $validator = Validator::make($data, [
            'background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'selected_element_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_slide_border_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_slide_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_slide_part_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_slide_part_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_slide_part_used_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_slide_part_used_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            PresentationStyle::where('presentation_id', $presentation)->first()
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Style Success']);
        }
    }

    public function destroy($presentation)
    {
        Presentation::find($presentation)->delete();

        return response()->json(['message' => 'Delete Presentation Success!']);
    }
}
