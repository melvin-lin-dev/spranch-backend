<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'is_main',
        'title',
        'description'
    ];

    protected $with = [
        'slides',
        'style'
    ];

    public function slides()
    {
        return $this->hasMany(Slide::class, 'presentation_id')->with(['style', 'detail', 'relations']);
    }

    public function style()
    {
        return $this->hasOne(PresentationStyle::class, 'presentation_id');
    }
}
