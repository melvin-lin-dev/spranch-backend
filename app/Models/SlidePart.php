<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlidePart extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'id',
        'slide_id',
        'number'
    ];

    public function slide()
    {
        return $this->belongsTo(Slide::class, 'slide_id');
    }
}
