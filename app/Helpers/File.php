<?php

if (!function_exists('get_design_presentation_logo')) {
    function get_design_presentation_logo($file)
    {
        return config('app.url') . '/' . config('asset.design.presentation.logo') . '/' . $file;
    }
}

if (!function_exists('get_design_presentation_thumbnail')) {
    function get_design_presentation_thumbnail($file)
    {
        return config('app.url') . '/' . config('asset.design.presentation.thumbnail') . '/' . $file;
    }
}
