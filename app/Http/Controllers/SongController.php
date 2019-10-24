<?php

namespace App\Http\Controllers;

use App\Jobs\AlertUsersForNewSongs;

use Illuminate\Http\Request;

use Auth;

use App\User;

use App\Song;

use Illuminate\Http\Response;

use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{

	public function isLoggedIn()
	{
		return Auth::id();
	}

    public function store(Request $request) {

        $this->validate($request, [

            'name' => 'required|unique:songs',

            'file' => 'required',

            'visible' => 'required'

        ]);

        $model = new Song();

        $file = $request->file('file');

        $place = "". $this->getUserDir();

        $id = Auth::id();

        $mail=Auth::user()->email;

        $find= $model::where('user_id', $id)->first();

        if($find == '')
        {
            dispatch(new AlertUsersForNewSongs(User::find(Auth::id())));

        }

        if (Storage::putFileAs('/public/' . $this->getUserDir() . '/', $file, $request['name'] )) {


            return $model::create([

                    'name' => $request['name'],

                    'src'  =>  '/'.$place,

                    'user_id' => Auth::id(),

                    'visible' => $request['visible']

                ]);
        }

        return response()->json(['success'=>'You have successfully upload file.']);

    }

    private function getUserDir()
    {
        return Auth::user()->name . '_' . Auth::id();
    }

    public function get(Request $request)
    {

    	$model = new Song();

    	return $model::all();

    }

    public function src(Request $request)
    {

    	return storage_path();
    }

    public function getFile($place,$src)
    {

    	$path= '/public/'.$place.'/'.$src;

    	$file= Storage::get($path);

    	return (new Response($file,200))->header('Content-Type','audio/mpeg');

    }

}
