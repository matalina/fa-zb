<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $connection = 'fa_full';
    protected $guarded = ['id'];
}

