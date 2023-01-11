<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as BasePersonalAccessToken;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PersonalAccessToken extends BasePersonalAccessToken
{
    use HasUuids;
}
