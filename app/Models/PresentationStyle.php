<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentationStyle extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'presentation_id',
        'background_color',
        'selected_element_color'
    ];

    protected $hidden = [
        'presentation_id'
    ];
}
