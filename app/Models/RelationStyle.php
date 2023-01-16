<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationStyle extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'id',
        'relation_id',
        'background_color'
    ];

    protected $hidden = [
        'relation_id'
    ];
}
