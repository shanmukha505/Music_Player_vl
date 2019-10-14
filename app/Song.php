<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{

    protected $fillable = ['name', 'src' , 'visible', 'user_id'];

}
