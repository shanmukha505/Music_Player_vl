<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Song;


use Illuminate\Http\Response;

use Illuminate\Support\Facades\Storage;


class SongController extends Controller
{
    //



	public function isLoggedIn()
	{
		return Auth::id();
	}



    public function store(Request $request) {

    	
        $this->validate($request, [
            'name' => 'required|unique:songs',
            'file' => 'required'
        ]);
       
        $model = new Song();
        $file = $request->file('file');
        $place = "". $this->getUserDir();
        if (Storage::putFileAs('/public/' . $this->getUserDir() . '/', $file, $request['name'] )) {
        	
        	
            return $model::create([
                    'name' => $request['name'],
                    'src'  =>  '/'.$place,
                    'user_id' => Auth::id()
                ]);
        }



        return response()->json(['success'=>'You have successfully upload file.']);

       
    }

     private function getUserDir()
    {
        return Auth::user()->name . '_' . Auth::id();
    }

    public function get(Request $request) {

    	$model = new Song();
    	return $model::All();

    }

    public function src(Request $request) {

    	return storage_path();
    }

    public function getFile($place,$src)
    {

    	$path= '/public/'.$place.'/'.$src;

    	$file= Storage::get($path);

    	

    	return (new Response($file,200))->header('Content-Type','audio/mpeg'); 


    }

    public function usersongs()
    {
    	$user = DB::table('users')->where('name', 'John')->first();
    }

}
