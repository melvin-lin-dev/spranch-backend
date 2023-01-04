<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'presentation_id',
        'title',
        'description',
        'element_id',
        'detail_id'
    ];

    public $hidden = [
        'presentation_id',
        'slide_id',
        'detail_id'
    ];

    protected $with = [
        'element'
//        'style',
//        'detail',
//        'relations'
    ];

    public function element()
    {
        return $this->hasOne(Element::class, 'element_id');
    }

    public function style()
    {
        return $this->hasOne(SlideStyle::class, 'slide_id');
    }

    public function detail()
    {
        return $this->belongsTo(Presentation::class, 'detail_id');
    }

    public function relations()
    {
        return $this->hasMany(SlidePart::class, 'slide_id');
    }

//    protected $appends = [
//        'relation'
//    ];
//
//    public function getRelationAttribute()
//    {
//        return SlidePart::select('number')->where('slide_id', $this->id)->get()
//            ->map(function ($slidePart) {
//                return $slidePart->number;
//            });
//    }
}
