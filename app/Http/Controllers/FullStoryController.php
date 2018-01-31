<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FullStory;

class FullStoryController extends Controller
{
    public function __invoke()
    {
        $story = FullStory::orderBy('start_date','Asc')->get();
        
        return view('story.full')
            ->with('story', $story);
    }
   
}
