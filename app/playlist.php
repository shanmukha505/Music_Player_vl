<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class playlist extends Model
{
    protected $fillable = ['name' , 'user_id' , 'song_id' ,'song_name'];
}
