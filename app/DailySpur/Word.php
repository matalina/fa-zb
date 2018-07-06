<?php

namespace App\DailySpur;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $connection = 'spur';
    protected $guarded = ['id'];
}
