<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    //
    protected $fillable = ['date','from','to','user_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
