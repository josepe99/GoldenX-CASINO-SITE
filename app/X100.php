<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class X100 extends Model
{
    protected $guarded = [];
    protected $table = 'x100';

    protected $casts = [
        'bet' => 'float',
        'payout' => 'float',
        'settled' => 'integer',
    ];
}
