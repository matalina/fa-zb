<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $connection = 'fa_full';
    protected $guarded = ['id'];
}
