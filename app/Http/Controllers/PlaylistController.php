<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    //

    public function store(Request $request) {

    	
        
       
        $model = new Playlist();

        $model::create([
        
                    'name' => $request['name'],
        
                    'user_id' => Auth::id(),
        
                    'song_id' => $request['song_id'],

                    'song_name' => $request['song_name'],
        
                ]);

    }
}
