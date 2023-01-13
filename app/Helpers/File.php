<?php

use Illuminate\Http\Request;

if (!function_exists('upload_file')) {
    function upload_file(Request $r, $key, $path)
    {
        $fileName = get_unique_file_name($r, $key);
        $file = $r->file($key);
        $file->move($path, $fileName);
        return $fileName;
    }
}

if (!function_exists('get_unique_file_name')) {
    function get_unique_file_name(Request $r, $key)
    {
        $file = $r->file($key);
        return rand() . time() . rand() . $file->getClientOriginalName();
    }
}

if (!function_exists('get_design_presentation_logo_path')) {
    function get_design_presentation_logo_path()
    {
        return config('app.url') . config('asset.design.presentation.logo');
    }
}

if (!function_exists('get_design_presentation_logo')) {
    function get_design_presentation_logo($fileName)
    {
        return get_design_presentation_logo_path() . '/' . $fileName;
    }
}

if (!function_exists('get_design_presentation_thumbnail_path')) {
    function get_design_presentation_thumbnail_path()
    {
        return config('app.url') . config('asset.design.presentation.thumbnail');
    }
}

if (!function_exists('get_design_presentation_thumbnail')) {
    function get_design_presentation_thumbnail($fileName)
    {
        return get_design_presentation_thumbnail_path() . '/' . $fileName;
    }
}
