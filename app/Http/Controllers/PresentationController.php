<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use App\Models\PresentationStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresentationController extends Controller
{
    public function index()
    {
        // TODO: USER ID
        $presentations = Presentation::where(['is_main' => true])->get();

        return response()->json([
            'data' => $presentations
        ]);
    }

    public function getFavoritedPresentations()
    {
        // TODO: USER ID
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

    public function store(Request $r)
    {
        $data = $r->only(['is_main']);
        $validator = Validator::make($data, [
            'is_main' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
//            $data['user_id'] = Auth::user()['id'];
            $presentation = Presentation::create($data);

            PresentationStyle::create([
                'relation_id' => $presentation->id,
            ]);

            return response()->json(['data' => $presentation]);
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
            Presentation::find($presentation)
                ->fill([
                    'image' => upload_file($r, 'logo', get_design_presentation_logo_path()),
                    'thumbnail' => upload_file($r, 'thumbnail', get_design_presentation_thumbnail_path())
                ])
                ->save();

            return response()->json(['message' => 'Update Images Success']);
        }
    }

    public function manageFavorite(Request $r, $presentation)
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
            'background_color', 'selected_element_color', 'first_border_color', 'first_background_color',
            'first_part_color', 'first_part_background_color', 'first_part_used_color', 'first_part_used_background_color'
        ]);
        $validator = Validator::make($data, [
            'background_color' => ['required', 'integer', 'min:50', 'max:300'],
            'selected_element_color' => ['required', 'integer', 'min:50', 'max:300'],
            'first_border_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_part_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_part_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_part_used_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
            'first_part_used_background_color' => ['required', 'regex:/^#[a-zA-Z0-9]{6}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            PresentationStyle::where('presentation_id', $presentation)
                ->fill($data)
                ->save();

            return response()->json(['message' => 'Update Style Success']);
        }
    }
}
