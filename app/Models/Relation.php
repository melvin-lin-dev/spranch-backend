<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'slide_part1_id',
        'slide_part2_id',
        'title1',
        'title2',
    ];

    protected $hidden = [
        'slide_part1_id',
        'slide_part2_id'
    ];

    protected $with = [
        'slide_part1',
        'slide_part2',
        'style'
    ];

    public function slide_part1()
    {
        return $this->belongsTo(SlidePart::class, 'slide_part1_id')->with('slide');
    }

    public function slide_part2()
    {
        return $this->belongsTo(SlidePart::class, 'slide_part2_id')->with('slide');
    }

    public function style()
    {
        return $this->hasOne(RelationStyle::class, 'relation_id');
    }
}
