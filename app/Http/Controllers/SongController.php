<?php

namespace App\Http\Controllers;

use App\Notifications\NewUserSongsUpload;
use Illuminate\Http\Request;
use Notification;
use Auth;

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
        $find= $model::where('user_id', $id)->first();
        if($find == '')
        Notification::route('mail','music@mail.com')->notify(new NewUserSongsUpload);

        if (Storage::putFileAs('/public/' . $this->getUserDir() . '/', $file, $request['name'] )) {


            return $model::create([

                    'name' => $request['name'],

                    'src'  =>  '/'.$place,

                    'user_id' => Auth::id(),

                    'visible' => $request['visible']

                ]);
        }
        $id = Auth::id();
        $find= DB::table('songs')->where('user_id', $id)->first();


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
