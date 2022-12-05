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
        'color',
        'background_color'
    ];

    protected $hidden = [
        'slide_id'
    ];
}
