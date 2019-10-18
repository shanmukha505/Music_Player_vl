<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Playlist;

use Illuminate\Http\Response;

class PlaylistController extends Controller
{
    //

    public function store(Request $request) {

        $model = new Playlist();

        return $model::create([
        
                    'name' => $request['name'],
        
                    'user_id' => Auth::id(),
        
                    'song_id' => $request['song_id'],

                    'song_name' => $request['song'],
        
                ]);

    }
}
