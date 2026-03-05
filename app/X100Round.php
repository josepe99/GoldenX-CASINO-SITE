<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class X100Round extends Model
{
    protected $table = 'x100_rounds';

    protected $guarded = [];

    protected $dates = [
        'started_at',
        'betting_ends_at',
        'spinning_ends_at',
        'settled_at',
        'created_at',
        'updated_at',
    ];
}

