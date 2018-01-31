<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FullStory extends Model
{
    protected $table = 'full_story';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $connection = 'sqlite';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date',
    ];
}
