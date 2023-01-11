<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlideStyle extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'slide_id',
        'top',
        'left',
        'width',
        'height',
        'border_color',
        'background_color',
        'part_color',
        'part_background_color',
        'part_used_color',
        'part_used_background_color',
        'z_index'
    ];

    protected $hidden = [
        'slide_id'
    ];
}
