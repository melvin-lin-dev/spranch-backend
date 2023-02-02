<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'is_main',
        'title',
        'description',
        'is_favorite',
        'logo',
        'thumbnail'
    ];

    public $hidden = [
        'user_id',
        'logo',
        'thumbnail'
    ];

    public $appends = [
        'logo_src',
        'thumbnail_src'
    ];

    public function getLogoSrcAttribute()
    {
        if ($this->logo) {
            return get_design_presentation_logo($this->logo);
        }
    }

    public function getThumbnailSrcAttribute()
    {
        if ($this->thumbnail) {
            return get_design_presentation_thumbnail($this->thumbnail);
        }
    }

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
