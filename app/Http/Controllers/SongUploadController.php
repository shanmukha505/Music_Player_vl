<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\SongUpload;

use App\User;

use Auth;

use DB;

class SongUploadController extends Controller
{



	public function __construct()
	{

		//$this->middleware('auth');

	}


    public function uploadFile(Request $request) {

    	$file = $request->file->getClientOriginalName(); 

    	$id = Auth::user()->getId();
    	
    	$path = $request->file->storeAs('public/music', $file);

    	$user_name= DB::select('select name from users where id=?',[$id]);

    	if(Storage::disk($user_name)->put($path.'/'.$filename,  File::get($file))) {
        
        $id = Auth::user()->getId();

         $input['songname'] = $file;

         $input['src'] = $path;

         $input['user_id'] = $id;

        $create= DB::table('songsdata')->insert($input);

    }

        return response()->json(['success'=>'You have successfully upload file.']);

       
    }

    public function create() {

    return view('/file/create');
}

    public function index() {

    $files = SongUpload::all();

    return view('files.index', compact('files'));
}
	public function isLoggedIn()
	{
		return Auth::id();
	}
}
